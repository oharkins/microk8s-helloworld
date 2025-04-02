# Build stage
FROM php:8.2-apache AS builder

# Install PostgreSQL client and PHP PDO PostgreSQL extension
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Final stage
FROM php:8.2-apache

# Install PostgreSQL client and PHP PDO PostgreSQL extension
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Create non-root user
RUN useradd -m -u 1000 appuser \
    && chown -R appuser:appuser /var/www/html

# Copy the application files
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY index.php /var/www/html/
RUN chown appuser:appuser /var/www/html/index.php

# Enable Apache modules
RUN a2enmod rewrite

# Configure Apache to listen on port 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf \
    && sed -i 's/:80/:8080/' /etc/apache2/sites-enabled/*.conf

# Switch to non-root user
USER appuser

# Add labels
LABEL maintainer="Odis Harkins <odisjamesharkins@gmail.com>"
LABEL version="1.0"
LABEL description="PHP application with PostgreSQL support" 