Clone o Repositório: git clone https://github.com/fabianochaves/travel-service.git

Acesse a pasta da aplicação: cd travel-service

Suba os Containers do Docker: docker compose up -d

Acesse o container do serviço laravel (travel-service-laravel) via terminal: docker exec -it ID_DO_CONTAINER bash

Rode a instalação das dependências via Composer: composer install

Na raiz do projeto, copie o arquivo .env_example e renomeie para .env, o .env_example já está configurado o banco de dados e o serviço de e-mail.

Gere a chave via JWT no arquivo .env: php artisan jwt:secret

Rode a migração (migrations) das tabelas do banco de dados juntamente com a criação de usuários (seed): php artisan migrate --seed
