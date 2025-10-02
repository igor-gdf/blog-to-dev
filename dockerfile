# Use uma imagem base com PHP 5.6 e Apache, compatível com CakePHP 2.x
FROM php:5.6-apache

# A imagem base usa Debian 9 "Stretch", cujos repositórios de pacotes foram movidos para um arquivo.
# O comando abaixo atualiza a lista de fontes do apt para apontar para os repositórios arquivados.
RUN echo "deb http://archive.debian.org/debian/ stretch main" > /etc/apt/sources.list \
    && echo "deb http://archive.debian.org/debian-security stretch/updates main" >> /etc/apt/sources.list

# Instala as dependências do sistema e as extensões PHP necessárias
# Adicionamos --allow-unauthenticated para aceitar pacotes com assinaturas expiradas do repositório arquivado.
RUN apt-get update && apt-get install -y --allow-unauthenticated \
    libpq-dev \
    libicu-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql intl

# --- NOVA LINHA PARA CORRIGIR O FUSO HORÁRIO ---
# Define o fuso horário para o PHP para evitar avisos. Usei o de São Paulo como exemplo.
RUN printf '\ndate.timezone = "America/Sao_Paulo"\n' > /usr/local/etc/php/conf.d/tzone.ini

# Habilita o mod_rewrite do Apache para as URLs amigáveis do CakePHP
RUN a2enmod rewrite

# Define o diretório de trabalho dentro do contêiner
WORKDIR /var/www/html