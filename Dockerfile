# 1. Usar imagen base de PHP con Apache
FROM php:8.2-apache

# 2. Instalar dependencias, drivers y limpiar caché
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

# 3. Instalar Node.js y NPM
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 4. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Configurar directorio de trabajo
WORKDIR /var/www/html

# 6. Copiar los archivos de tu proyecto
COPY . .

# 7. Instalar dependencias y compilar assets (CORREGIDO)
# Todo en un solo RUN para evitar errores de sintaxis y capas extra
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Configurar Apache para apuntar a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 9. Exponer puerto
EXPOSE 80

# 10. Comando de inicio
CMD php artisan migrate --force && apache2-foreground