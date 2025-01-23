# Base image
FROM php:8.1-fpm

# Copiar arquivos do projeto
WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia os arquivos do projeto, incluindo o composer.json
COPY travel-requests/ ./


# Instalar dependências
RUN composer install

# Expor as portas
EXPOSE 80

# Comando para iniciar a aplicação
CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]