# Menggunakan image base resmi dari mlocati yang sudah menyertakan installer extension otomatis
FROM php:8.1-apache

# Instalasi mysqli secara instan tanpa proses compile manual yang berat
RUN curl -sSL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o /usr/local/bin/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions mysqli

# Menyalin seluruh kode WebGIS ke dalam folder server Apache
COPY . /var/www/html/

# Memberikan hak akses penuh kepada Apache untuk membaca script PHP
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Membuka jalur port 80
EXPOSE 80