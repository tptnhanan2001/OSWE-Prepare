FROM php:8.2-apache

# Install MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html/

# Create uploads directory
RUN mkdir -p /var/www/html/uploads && \
    chmod 777 /var/www/html/uploads

# Set working directory
WORKDIR /var/www/html

# Expose port
EXPOSE 80

