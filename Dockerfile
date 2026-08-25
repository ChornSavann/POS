FROM php:8.2-fpm

# ຕິດຕັ້ງ System Dependencies & PHP Extensions ផ្សេងៗ
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nginx

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions រួមទាំង pdo_pgsql សម្រាប់ PostgreSQL
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# កំណត់ Working Directory
WORKDIR /var/www

# Copy Source Code ទាំងអស់ចូល
COPY . /var/www

# ຕິດຕັ້ງ Composer Dependencies
RUN composer install --no-dev --optimize-autoloader

# កំណត់សិទ្ធិ Folder Storage និង Bootstrap/Cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy Nginx Configuration (ຖົមាន) ឬកែសម្រួល Default config
COPY docker/nginx.conf /etc/nginx/sites-available/default

EXPOSE 80

# Script សម្រាប់ Start Nginx និង PHP-FPM ព្រមទាំង Run Migration (ដោយមិនធ្វើ Config Cache មុន)
CMD php artisan migrate --force && \
    php-fpm -D && \
    nginx -g 'daemon off;'