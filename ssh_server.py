from flask import Flask, request, jsonify
import paramiko
import threading

app = Flask(__name__)

# Dicionário para armazenar conexões SSH ativas
ssh_connections = {}
lock = threading.Lock()

@app.route('/execute', methods=['POST'])
def execute_command():
    data = request.json
    server_id = data.get('server_id')
    command = data.get('command')
    host = data.get('host')
    username = data.get('username')
    private_key_path = data.get('private_key_path')

    if not server_id or not command or not host or not username or not private_key_path:
        return jsonify({'error': 'server_id, command, host, username, and private_key_path are required'}), 400

    with lock:
        if server_id not in ssh_connections:
            try:
                # Carregar a chave privada
                private_key = paramiko.RSAKey.from_private_key_file(private_key_path)

                # Criar nova conexão SSH
                ssh = paramiko.SSHClient()
                ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
                ssh.connect(host, username=username, pkey=private_key)

                # Armazenar a conexão no dicionário
                ssh_connections[server_id] = ssh
            except Exception as e:
                return jsonify({'error': f'Failed to connect: {str(e)}'}), 500

        # Executar o comando
        ssh = ssh_connections[server_id]
        try:
            stdin, stdout, stderr = ssh.exec_command(command)
            output = stdout.read().decode('utf-8')
            error = stderr.read().decode('utf-8')
            return jsonify({'output': output, 'error': error})
        except Exception as e:
            return jsonify({'error': f'Failed to execute command: {str(e)}'}), 500

@app.route('/close', methods=['POST'])
def close_connection():
    data = request.json
    server_id = data.get('server_id')

    if not server_id:
        return jsonify({'error': 'server_id is required'}), 400

    with lock:
        if server_id in ssh_connections:
            ssh = ssh_connections.pop(server_id)
            ssh.close()
            return jsonify({'message': 'Connection closed successfully'})
        else:
            return jsonify({'error': 'No active connection for the given server_id'}), 404

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
