FROM php:8.2-apache

# 必要なライブラリをインストール
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# アプリのコードをコピー
COPY ./app /var/www/html/
