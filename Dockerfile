# 1. Usar imagen base de PHP con Apache
FROM php:8.2-apache

# 2. Instalar dependencias esenciales, drivers y limpiar caché
# NOTA: Ya no instalamos Node.js ni NPM porque subiremos los assets compilados.
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql mbstring zip \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 3. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Configurar directorio de trabajo
WORKDIR /var/www/html

# 5. Copiar los archivos de tu proyecto
COPY . .

# 6. Instalar dependencias de PHP y ajustar permisos
# NOTA: Eliminamos 'npm install' y 'npm run build' de aquí
RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Configurar Apache para apuntar a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 8. Exponer puerto
EXPOSE 80

# 9. Comando de inicio
CMD php artisan migrate --force && apache2-foreground