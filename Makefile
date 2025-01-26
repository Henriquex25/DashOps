PYTHON_SERVER_SCRIPT=ssh_server.py
VENV_PATH=venv/bin/activate
SAIL_COMMAND=./vendor/bin/sail

.PHONY: help
help:
	@echo "Comandos disponíveis:"
	@echo "  make start         - Inicia o servidor Python e o Laravel Sail"
	@echo "  make stop          - Encerra o servidor Python e o Laravel Sail"
	@echo "  make start-python  - Inicia o servidor Python"
	@echo "  make stop-python   - Encerra o servidor Python"
	@echo "  make start-sail    - Inicia o Laravel Sail"
	@echo "  make stop-sail     - Encerra o Laravel Sail"

# Inicia ambos os serviços
.PHONY: start
start:
	@echo "Iniciando o servidor Python e o Laravel Sail..."
	@bash -c "source $(VENV_PATH) && nohup python $(PYTHON_SERVER_SCRIPT) > python_server.log 2>&1 &"
	@$(SAIL_COMMAND) up -d
	@echo "Todos os serviços foram iniciados. Logs do servidor Python em 'python_server.log'."

# Encerra ambos os serviços
.PHONY: stop
stop:
	@echo "Encerrando o servidor Python e o Laravel Sail..."
	@pkill -f $(PYTHON_SERVER_SCRIPT) || echo "Nenhum servidor Python em execução."
	@$(SAIL_COMMAND) down
	@echo "Todos os serviços foram encerrados."

# Inicia o servidor Python
.PHONY: start-python
start-python:
	@echo "Iniciando o servidor Python..."
	@bash -c "source $(VENV_PATH) && nohup python $(PYTHON_SERVER_SCRIPT) > python_server.log 2>&1 &"
	@echo "Servidor Python iniciado e rodando em segundo plano. Logs em 'python_server.log'."

# Encerra o servidor Python
.PHONY: stop-python
stop-python:
	@echo "Encerrando o servidor Python..."
	@pkill -f $(PYTHON_SERVER_SCRIPT) || echo "Nenhum servidor Python em execução."
	@echo "Servidor Python encerrado."

# Inicia o Laravel Sail
.PHONY: start-sail
start-sail:
	@echo "Iniciando o Laravel Sail..."
	@$(SAIL_COMMAND) up -d
	@echo "Laravel Sail iniciado."

# Encerra o Laravel Sail
.PHONY: stop-sail
stop-sail:
	@echo "Encerrando o Laravel Sail..."
	@$(SAIL_COMMAND) down
	@echo "Laravel Sail encerrado."
