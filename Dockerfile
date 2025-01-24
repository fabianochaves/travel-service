# Usar uma imagem base com PHP 8.1
FROM php:8.1-apache

# Instalar dependências do sistema necessárias (curl, git, unzip, etc.)
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Habilitar o módulo rewrite do Apache (necessário para o Laravel)
RUN a2enmod rewrite

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Expôr a porta 80 para o servidor Apache
EXPOSE 80

# Comando de inicialização padrão
CMD ["apache2-foreground"]
