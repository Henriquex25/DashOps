from flask import Flask, request, jsonify
import paramiko
import threading

app = Flask(__name__)

# Dicionário para armazenar conexões SSH ativas
ssh_connections = {}
lock = threading.Lock()

SUCCESS_STATUS = 'success'
ERROR_STATUS = 'error'


def test_ssh_connection(ssh):
    """
    Testa se a conexão SSH está ativa.
    """
    try:
        # Envia um comando inofensivo para verificar a conexão
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
        if server_id in ssh_connections:
            ssh = ssh_connections[server_id]
            if test_ssh_connection(ssh):
                return jsonify({
                    'status': SUCCESS_STATUS,
                    'message': 'Connection already active'
                }), 200
            else:
                # Remove a conexão inativa do dicionário
                ssh_connections.pop(server_id)

        # Cria uma nova conexão SSH
        try:
            private_key = paramiko.RSAKey.from_private_key_file(private_key_path)
            ssh = paramiko.SSHClient()
            ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
            ssh.connect(host, username=username, pkey=private_key)

            # Armazena a conexão no dicionário
            ssh_connections[server_id] = ssh
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
        if not server_id in ssh_connections or not test_ssh_connection(ssh_connections[server_id]):
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
        if server_id in ssh_connections:
            ssh = ssh_connections[server_id]
            if not test_ssh_connection(ssh):
                # Conexão não está mais ativa
                ssh_connections.pop(server_id)
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
        if server_id in ssh_connections:
            ssh = ssh_connections.pop(server_id)
            ssh.close()
            return jsonify({
                'status': SUCCESS_STATUS,
                'message': 'Connection closed successfully',
            }), 200
        else:
            return jsonify({
                'status': ERROR_STATUS,
                'error': f'No active connection for server_id {server_id}',
            }), 404


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8000)
