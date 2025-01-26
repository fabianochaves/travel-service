
1) Clone o Repositório: git clone https://github.com/fabianochaves/travel-service.git

2) Acesse a pasta a aplicação: cd travel-service

3) Baixe o Docker Desktop caso não possua, e após tê-lo instalado, suba os Containers do Docker com o comando: docker compose up -d

4) Verifique o ID do container laravel (travel-service-laravel) e acesse o mesmo via terminal: docker exec -it ID_DO_CONTAINER bash

5) Dentro do container laravel, rode a instalação das dependências via Composer: composer install

6) Na raiz do projeto, copie o arquivo .env_example e renomeie para .env via comando: cp .env.example .env

OBS: o .env_example já está configurado o banco de dados, basta apenas configurar o serviço de e-mail:

MAIL_MAILER=smtp
MAIL_HOST=SEU_SERVICO_SMTP
MAIL_PORT=SUA_PORTA
MAIL_USERNAME="SEU_EMAIL"
MAIL_PASSWORD="SUA_SENHA"
MAIL_ENCRYPTION="SUA_CRIPTOGRAFIA"
MAIL_FROM_ADDRESS="SEU_EMAIL"
MAIL_FROM_NAME="${APP_NAME}"

7) Gere a chave via JWT no arquivo .env: php artisan jwt:secret

8) Rode a migração (migrations) das tabelas do banco de dados juntamente com a criação de usuários (seed): php artisan migrate --seed

9) Execute os testes com o PHPUnit, rodando os comandos abaixo individualmente:

	a) ./vendor/bin/phpunit --filter LoginTest
	b) ./vendor/bin/phpunit --filter TravelOrderCreateTest
	c) ./vendor/bin/phpunit --filter TravelOrderUpdateTest
	d) ./vendor/bin/phpunit --filter TravelOrderShowTest
	e) ./vendor/bin/phpunit --filter TravelOrderIndexTest

OBS: Todas as rotinas de testes foram programadas para executar o teste e apagar apenas os dados gerados nos mesmos.