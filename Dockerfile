FROM php:8.2-apache

# Install PostgreSQL libraries (required for Render DB)
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql

# Enable Apache mod_rewrite (useful for routing)
RUN a2enmod rewrite

# Copy all your website files into the container
COPY . /var/www/html/

# Set permissions so PHP can write to the uploads folder
RUN mkdir -p /var/www/html/uploads && chmod -R 777 /var/www/html/uploads

# Tell Render to use port 80
EXPOSE 80
