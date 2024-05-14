# Utiliza la imagen base oficial de PHP con FPM
FROM php:8.3-fpm

# Argumento para establecer el UID del usuario dentro del contenedor
ARG UID=1000

# Instala las extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libwebp-dev \
    libxpm-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install pdo pdo_mysql mysqli mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuración de usuario
RUN useradd -G www-data,root -u $UID -d /home/appuser appuser
RUN mkdir -p /home/appuser/.composer && \
    chown -R appuser:appuser /home/appuser

# Directorio de trabajo
WORKDIR /var/www/html

# Configurar volumen
VOLUME /var/www/html

# Cambiar al usuario no root
USER appuser

# Copiar configuraciones de PHP si es necesario
COPY php.ini /usr/local/etc/php/conf.d/custom-php-config.ini

# Copiar configuración de Xdebug, ajustar según necesidad de desarrollo
COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


# Exponer puerto
EXPOSE 9000

# Comando para ejecutar PHP-FPM
CMD ["php-fpm"]
