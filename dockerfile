# Usa una imagen oficial de PHP con extensiones necesarias
FROM php:8.2-fpm

# Instala herramientas y extensiones requeridas
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip pdo_pgsql \
    && docker-php-ext-enable pdo_pgsql

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Instala dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Crea un enlace simbólico para el almacenamiento
RUN php artisan storage:link

# Cachea configuraciones y rutas
RUN php artisan config:cache && php artisan route:cache

# Realiza las migraciones
RUN php artisan migrate --force

# Establece permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expone el puerto
CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"]
EXPOSE 8000
