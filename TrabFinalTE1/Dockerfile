# Usar a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instalar extensões do PHP necessárias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip

# Configurar o diretório de trabalho
WORKDIR /var/www/html

# Habilitar mod_rewrite para URLs amigáveis
RUN a2enmod rewrite

# Copiar o arquivo de configuração do VirtualHost para usar o diretório /public
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Configurar o PHP para upload de arquivos
RUN echo "upload_max_filesize = 10M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 10M" >> /usr/local/etc/php/conf.d/uploads.ini

# Expor a porta 80
EXPOSE 80

# Definir permissões para o usuário www-data
RUN chown -R www-data:www-data /var/www/html

# Iniciar Apache
CMD ["apache2-foreground"]