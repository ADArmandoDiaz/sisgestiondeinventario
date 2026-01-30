# 1. Usar imagen base de PHP con Apache
FROM php:8.2-apache

# 2. Instalar dependencias del sistema y drivers
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    libzip-dev \
    libonig-dev \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql mbstring zip \
    && a2enmod rewrite
# 3. Instalar Node.js y NPM (Necesario para tu 'npm run build')
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 4. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Configurar directorio de trabajo
WORKDIR /var/www/html

# 6. Copiar los archivos de tu proyecto al servidor
COPY . .

# 7. Ejecutar los comandos de instalación (Composer y NPM)
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Configurar Apache para que apunte a la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 9. Exponer el puerto 80
EXPOSE 80

# 10. Comando de inicio (Ejecutar migraciones y prender Apache)
CMD php artisan migrate --force && apache2-foreground