# Use the official PHP image with Apache as the base
FROM php:8.2-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# ----------------------------------------------------------------------
# 1. Install System Dependencies (Needed for PHP Extensions)
# ----------------------------------------------------------------------
RUN apt-get update && apt-get install -y \
    # The 'libpq-dev' package provides the core PostgreSQL client libraries
    libpq-dev \
    # Other system tools needed by your project
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    # Clean up to keep the image small
    && rm -rf /var/lib/apt/lists/*

# ----------------------------------------------------------------------
# 2. Install PHP Extensions
# ----------------------------------------------------------------------
# The 'docker-php-ext-install' command compiles and enables the PHP driver.
# 'pdo_pgsql' is the PDO driver for connecting to Postgres.
# 'pgsql' is the traditional Postgres driver.
RUN docker-php-ext-install pdo mbstring zip opcache pdo_pgsql pgsql
RUN a2enmod rewrite

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the Composer configuration files
COPY composer.json composer.lock ./

# Install Composer dependencies
# Use --no-dev if you only want production dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy the rest of your application code into the container
# This copies your PHP files, HTML, CSS, etc.
COPY . .

# Expose port 80 (Apache's default)
EXPOSE 80

# The default command for php:apache starts the Apache web server, 
# so we don't need a specific CMD instruction here.