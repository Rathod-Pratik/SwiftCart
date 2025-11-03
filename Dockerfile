# ==========================================
# Stage 1 — Composer dependencies
# ==========================================
FROM composer:2 AS vendor

WORKDIR /app

# Copy composer files for dependency caching
COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-ansi

# ==========================================
# Stage 2 — PHP + Apache (Render-ready)
# ==========================================
FROM php:8.2-apache

ENV APACHE_RUN_USER=www-data \
    APACHE_RUN_GROUP=www-data

# Install system dependencies + PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
        libzip-dev \
        libonig-dev \
        git \
        unzip \
        ca-certificates \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip opcache \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers

WORKDIR /var/www/html

# Copy source code + dependencies
COPY . /var/www/html
COPY --from=vendor /app/vendor /var/www/html/vendor

# Set permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# -------------------------------------------
# Startup Script for Render Dynamic Port
# -------------------------------------------
RUN cat > /usr/local/bin/start-apache.sh <<'SH'
#!/usr/bin/env bash
set -e

# Get Render dynamic port (default fallback for local)
PORT="${PORT:-10000}"
echo "[startup] Using PORT=${PORT}"

# Update Apache to listen on this port
sed -i "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost .*>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Add ServerName to avoid warnings
if ! grep -q "^ServerName" /etc/apache2/apache2.conf 2>/dev/null; then
  echo "ServerName localhost" >> /etc/apache2/apache2.conf
fi

# Ensure .htaccess is allowed
if ! grep -q "<Directory /var/www/html>" /etc/apache2/apache2.conf 2>/dev/null; then
  cat >> /etc/apache2/apache2.conf <<'EOF'
<Directory /var/www/html>
  AllowOverride All
  Require all granted
</Directory>
EOF
fi

echo "[startup] Apache configured and starting..."
exec apache2-foreground
SH

RUN chmod +x /usr/local/bin/start-apache.sh

# Expose a default port (Render will override this)
EXPOSE 10000

# Use startup script as container entrypoint
CMD ["/usr/local/bin/start-apache.sh"]
