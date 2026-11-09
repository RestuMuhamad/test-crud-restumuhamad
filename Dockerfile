FROM php:5.6-apache
# Ekstensi mysql_* wajib untuk soal; PHP 5.6 masih membawa source ext/mysql.
RUN docker-php-ext-install mysql
