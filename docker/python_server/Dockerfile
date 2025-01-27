# Base image
FROM python:3.9-slim

# Define o diretório de trabalho dentro do container
WORKDIR /app

# Instala as dependências do projeto
RUN pip install flask paramiko

# Copia o script do servidor Python para o container (a partir da raiz do projeto Laravel)
COPY ssh_server.py /app/

# Expõe a porta 8000 usada pelo Flask
EXPOSE 8000

# Comando padrão para iniciar o servidor
CMD ["python", "ssh_server.py"]
