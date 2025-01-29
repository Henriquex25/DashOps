from flask import Flask, request, jsonify
import paramiko
import threading
import time
import requests
import os
from dotenv import load_dotenv

load_dotenv()
app = Flask(__name__)

url="http://laravel.test/api/broadcast-message"

# Dicionário para armazenar conexões SSH ativas
connections = {}
lock = threading.Lock()

SUCCESS_STATUS = 'success'
ERROR_STATUS = 'error'

def send_message_to_laravel_api(data: dict = {}, url: str = url):
    requests.post(url, json=data, headers={
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': os.getenv('API_TOKEN')
    })

def test_ssh_connection(ssh):
    try:
        ssh.exec_command("echo Connection test")
        return True
    except Exception:
        return False

@app.route('/connect', methods=['POST'])
def connect_to_server():
    data = request.json
    server_id = data.get('server_id')
    host = data.get('host')
    username = data.get('username')
    private_key_path = data.get('private_key_path')

    if not server_id or not host or not username or not private_key_path:
        return jsonify({
            'status': SUCCESS_STATUS,
            'message': 'server_id, host, username, and private_key_path are required'
        }), 400

    with lock:
        # Verifica se a conexão já existe e está ativa
        if server_id in connections:
            ssh = connections[server_id]['ssh']
            if test_ssh_connection(ssh):
                connections[server_id]["last_activity"] = time.time()
                return jsonify({
                    'status': SUCCESS_STATUS,
                    'message': 'Connection already active'
                }), 200
            else:
                # Remove a conexão inativa do dicionário
                connections.pop(server_id)

        # Cria uma nova conexão SSH
        try:
            private_key = paramiko.RSAKey.from_private_key_file(private_key_path)
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            ssh.connect(host, username=username, pkey=private_key)

            # Cria um canal interativo
            channel = ssh.invoke_shell()

            # Armazena a conexão no dicionário
            connections[server_id] = {
                'ssh': ssh,
                'channel': channel,
                'last_activity': time.time()
            }

            return jsonify({
                'status': SUCCESS_STATUS,
                'message': 'Connection established successfully',
            }), 200

        except Exception as e:
            return jsonify({
                'status': ERROR_STATUS,
                'message': str(e)
            }), 500

@app.route('/check_connection', methods=['POST'])
def check_connection():
    server_id = request.json.get('server_id')

    if not server_id:
        return jsonify({
            'status': ERROR_STATUS,
            'message': 'server_id is required',
        }), 400

    with lock:
        if not server_id in connections or not test_ssh_connection(connections[server_id]['ssh']):
            return jsonify({
                'status': SUCCESS_STATUS,
                'message': 'Connection is not active',
                'is_connected': False
            }), 404

        return jsonify({
            'status': SUCCESS_STATUS,
            'message': 'Connection is active',
            'is_connected': True
        }), 200

@app.route('/command', methods=['POST'])
def execute_command():
    data = request.json
    server_id = data.get('server_id')
    command = data.get('command')

    if not server_id or not command:
        return jsonify({
            'status': ERROR_STATUS,
            'message': 'server_id and command are required',
        }), 400

    with lock:
        # Verifica se a conexão existe e está ativa
        if server_id in connections:
            connection = connections[server_id]
            ssh = connection['ssh']
            if not test_ssh_connection(ssh):
                # Conexão não está mais ativa
                connections.pop(server_id)

                return jsonify({
                    'status': ERROR_STATUS,
                    'message': 'Connection is no longer active',
                }), 500
        else:
            return jsonify({
                'status': ERROR_STATUS,
                'message': f'No active connection for server_id {server_id}',
            }), 404

        # Executa o comando
        try:
            stdin, stdout, stderr = ssh.exec_command(command)
            output = stdout.read().decode('utf-8')
            error = stderr.read().decode('utf-8')

            # Atualiza o tempo da última atividade
            connection["last_activity"] = time.time()

            return jsonify({
                'status': SUCCESS_STATUS,
                'output': output,
                'error': error
                }), 200

        except Exception as e:
            return jsonify({
                'status': ERROR_STATUS,
                'message': f'Failed to execute command: {str(e)}'
            }), 500
    data = request.json
    server_id = data.get('server_id')
    command = data.get('command')

    if not server_id or not command:
        return jsonify({
            'status': ERROR_STATUS,
            'message': 'server_id and command are required',
        }), 400

    with lock:
        # Verifica se a conexão existe
        if server_id not in connections:
            return jsonify({
                'status': ERROR_STATUS,
                'message': f'No active connection for server_id {server_id}',
            }), 404

        connection = connections[server_id]
        channel = connection["channel"]

        try:
            # Envia o comando no canal interativo
            channel.send(command + '\n')

            @stream_with_context
            def generate_output():
                # Envia uma mensagem fixa inicial
                yield "streaming funcionando\n"
                print("Enviado: streaming funcionando")

                count = 0
                ma

                # Enviar saídas enquanto estiverem disponíveis
                while True:
                    if channel.recv_ready():
                        chunk = channel.recv(1024).decode('utf-8')
                        print(f"Enviando chunk: {chunk}")
                        yield chunk
                    if channel.exit_status_ready():
                        break
                    time.sleep(0.5)

                # while channel.recv_ready():
                #     output = channel.recv(1024).decode("utf-8")
                #     print(output)
                #     yield output
                #     connection["last_activity"] = time.time()  # Atualiza a última atividade

                # yield "\n=== Command completed ===\n"

            # Retorna uma resposta em streaming
            return Response(generate_output(), content_type="text/plain", headers={
                'Connection': 'keep-alive',  # Mantém a conexão aberta
                'Cache-Control': 'no-cache'
            })

        except Exception as e:
            return jsonify({
                'status': ERROR_STATUS,
                'message': f'Failed to execute command: {str(e)}'
            }), 500

@app.route('/interactive_command', methods=['POST'])
def interactive_command():
    data = request.json
    user_id = data.get('user_id')
    server_id = data.get('server_id')
    command = data.get('command')

    if not server_id or not command or not user_id:
        return jsonify({
            'status': ERROR_STATUS,
            'message': 'server_id and command are required'
        }), 400

    with lock:
        # Verifica se a conexão existe
        if server_id not in connections:
            return jsonify({
                'status': ERROR_STATUS,
                'message': f'No active connection for server_id {server_id}'
            }), 404

        connection = connections[server_id]
        channel = connection["channel"]

        try:
            # Envia o comando e adiciona o delimitador
            delimiter = "COMMAND_EXECUTED\n"
            channel.send(f"{command}; echo '{delimiter}'")

            message_index = 0

            while True:
                if channel.recv_ready():
                    data = channel.recv(1024).decode("utf-8")

                    # Se encontrar o delimitador, encerra o loop
                    if delimiter in data:
                        break

                    send_message_to_laravel_api({
                        'server_id': server_id,
                        'user_id': user_id,
                        'message_index': message_index,
                        'message': data
                    })

                    message_index += 1

                time.sleep(0.1)

            connection["last_activity"] = time.time()  # Atualiza a última atividade

            return jsonify({
                'status': SUCCESS_STATUS,
            }), 200

        except Exception as e:
            return jsonify({
                'status': ERROR_STATUS,
                'message': f'Failed to execute interactive command: {str(e)}'
            }), 500

@app.route('/disconnect', methods=['POST'])
def close_connection():
    data = request.json
    server_id = data.get('server_id')

    if not server_id:
        return jsonify({
            'status': ERROR_STATUS,
            'error': 'server_id is required',
        }), 400

    with lock:
        if server_id in connections:
            connection = connections.pop(server_id)
            connection['ssh'].close()

            return jsonify({
                'status': SUCCESS_STATUS,
                'message': 'Connection closed successfully',
            }), 200
        else:
            return jsonify({
                'status': ERROR_STATUS,
                'error': f'No active connection for server_id {server_id}',
            }), 404

@app.route('/close_inactive_connections', methods=['POST'])
def close_inactive_connections():
    data = request.json
    max_inactive_time = data.get('max_inactive_time') # in seconds

    if max_inactive_time is None:
        return jsonify({
            'status': ERROR_STATUS,
            'message': 'max_inactive_time is required'
        }), 400

    with lock:
        inactive_connections = []
        current_time = time.time()

        # Encontra conexões inativas
        for server_id, connection in list(connections.items()):
            if (current_time - connection["last_activity"]) > max_inactive_time:
                inactive_connections.append(server_id)
                connection["ssh"].close()
                connections.pop(server_id)

    return jsonify({
        'status': SUCCESS_STATUS,
        'closed_connections': inactive_connections,
        'message': f'{len(inactive_connections)} inactive connections closed'
    }), 200
