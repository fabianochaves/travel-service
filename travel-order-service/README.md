# Travel Service

## Configuração do Projeto

### Pré-requisitos
- [Docker Desktop](https://www.docker.com/products/docker-desktop) instalado na máquina.
- Acesso ao terminal para executar os comandos.

---

### Passo a Passo:

1) Clone o Repositório: git clone https://github.com/fabianochaves/travel-service.git

2) Utilize o terminal e acesse a pasta que clonou o repositório.

3) Acesse a pasta da aplicação: cd travel-service

4) Suba os Containers do Docker com o comando: docker compose up -d

5) Verifique o ID do container laravel (travel-service-laravel) e acesse o mesmo via terminal: docker exec -it ID_DO_CONTAINER bash

6) Dentro do container laravel, rode a instalação das dependências via Composer: composer install

7) Na raiz do projeto, copie o arquivo .env_example e renomeie para .env via comando: cp .env.example .env

OBS: o .env_example já está configurado o banco de dados, basta apenas configurar o serviço de e-mail:

    MAIL_MAILER=smtp

    MAIL_HOST=SEU_SERVICO_SMTP

    MAIL_PORT=SUA_PORTA

    MAIL_USERNAME="SEU_EMAIL"

    MAIL_PASSWORD="SUA_SENHA"

    MAIL_ENCRYPTION="SUA_CRIPTOGRAFIA"

    MAIL_FROM_ADDRESS="SEU_EMAIL"

    MAIL_FROM_NAME="${APP_NAME}"

8) Gere a chave via JWT no arquivo .env: php artisan jwt:secret

9) Rode a migração (migrations) das tabelas do banco de dados juntamente com a criação de usuários (seed): php artisan migrate --seed

10) Execute os testes com o PHPUnit, rodando os comandos abaixo individualmente:

	./vendor/bin/phpunit --filter LoginTest

	./vendor/bin/phpunit --filter TravelOrderCreateTest
    
    ./vendor/bin/phpunit --filter TravelOrderUpdateTest

	./vendor/bin/phpunit --filter TravelOrderShowTest

	./vendor/bin/phpunit --filter TravelOrderIndexTest

OBS: Todas as rotinas de testes foram programadas para executar o teste e apagar apenas os dados gerados nos mesmos.