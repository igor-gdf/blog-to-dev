FROM php:7.4-apache

# Instala as dependências do sistema e as extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libicu-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql intl

# Habilita o mod_rewrite do Apache para as URLs amigáveis do CakePHP
RUN a2enmod rewrite

# Define o diretório de trabalho dentro do contêiner
WORKDIR /var/www/html

RUN echo "date.timezone = \"America/Recife\"" > /usr/local/etc/php/conf.d/timezone.ini

# Fix permissions for CakePHP cache and logs directories
RUN mkdir -p app/tmp/cache/persistent app/tmp/cache/models app/tmp/cache/views app/tmp/logs && \
    chmod -R 777 app/tmp

# Add entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]