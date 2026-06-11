# 1. Menggunakan base image PHP resmi yang sudah dilengkapi server web Apache
FROM php:8.1-apache

# 2. Menginstall dan mengaktifkan ekstensi mysqli untuk koneksi database MySQL/MariaDB
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# 3. Menyalin seluruh source code aplikasi dari lokal ke direktori dokumen web Apache di dalam container
COPY . /var/www/html/

# 4. Mengatur hak akses (permissions) folder web agar server Apache dapat membaca file dengan lancar
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# 5. Membuka port 80 untuk akses browser ke WebGIS
EXPOSE 80