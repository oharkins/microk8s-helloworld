FROM php:8.2-apache

# Install PostgreSQL client and PHP PDO PostgreSQL extension
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copy the application files
COPY index.php /var/www/html/

# Enable Apache modules
RUN a2enmod rewrite 