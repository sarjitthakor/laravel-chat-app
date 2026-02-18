FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy project
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Build frontend
RUN npm install && npm run build

# Laravel permissions
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8000

# Laravel production start
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT}



# Old dockerfile content (for reference, not to be included in the final Dockerfile)

# FROM php:8.2-fpm

# # Install system dependencies
# RUN apt-get update && apt-get install -y \
#     git \
#     curl \
#     zip \
#     unzip \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     libpq-dev \
#     libzip-dev \
#     libicu-dev \
#     nodejs \
#     npm

# # Install PHP extensions (IMPORTANT)
# RUN docker-php-ext-install \
#     pdo \
#     pdo_mysql \
#     pdo_pgsql \
#     mbstring \
#     exif \
#     pcntl \
#     bcmath \
#     gd \
#     intl \
#     zip

# # Install Composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Set working directory
# WORKDIR /var/www

# # Copy project files
# COPY . .

# # Install PHP dependencies
# RUN composer install --no-dev --optimize-autoloader

# # Install Node dependencies and build assets
# RUN npm install
# RUN npm run build

# # Set permissions
# RUN chmod -R 775 storage bootstrap/cache

# EXPOSE 8000

# CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT}
