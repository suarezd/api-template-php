FROM php:8.4-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    bash \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip opcache \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configuration d'OPcache pour les performances maximales
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=32'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.validate_timestamps=1'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=0'; \
    echo 'opcache.preload=/var/www/html/src/Infrastructure/Symfony/config/preload.php'; \
    echo 'opcache.preload_user=www-data'; \
    echo 'opcache.jit=tracing'; \
    echo 'opcache.jit_buffer_size=128M'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Configuration d'APCu pour le cache en mémoire
RUN { \
    echo 'apc.enabled=1'; \
    echo 'apc.shm_size=128M'; \
    echo 'apc.ttl=7200'; \
    echo 'apc.enable_cli=0'; \
    echo 'apc.serializer=php'; \
} > /usr/local/etc/php/conf.d/apcu.ini

# Optimisations PHP générales
RUN { \
    echo 'memory_limit=256M'; \
    echo 'max_execution_time=30'; \
    echo 'max_input_time=60'; \
    echo 'post_max_size=20M'; \
    echo 'upload_max_filesize=20M'; \
    echo 'realpath_cache_size=4096K'; \
    echo 'realpath_cache_ttl=600'; \
    echo 'expose_php=Off'; \
} > /usr/local/etc/php/conf.d/performance.ini

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration PHP-FPM optimisée
RUN echo '[global]' > /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'error_log = /proc/self/fd/2' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'log_level = warning' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo '' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo '[www]' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'pm = dynamic' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'pm.max_children = 50' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'pm.start_servers = 10' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'pm.min_spare_servers = 5' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'pm.max_spare_servers = 20' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'pm.max_requests = 1000' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'request_terminate_timeout = 30s' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'request_slowlog_timeout = 10s' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'pm.status_path = /fpm-status' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'ping.path = /fpm-ping' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'ping.response = pong' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'access.log = /proc/self/fd/2' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'slowlog = /proc/self/fd/2' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'catch_workers_output = yes' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'decorate_workers_output = no' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'php_admin_flag[log_errors] = on' >> /usr/local/etc/php-fpm.d/zz-custom.conf && \
    echo 'php_admin_value[memory_limit] = 256M' >> /usr/local/etc/php-fpm.d/zz-custom.conf

# Configuration de l'utilisateur www-data avec l'UID/GID de l'hôte
ARG USER_ID=1000
ARG GROUP_ID=1000

RUN if [ ${USER_ID} -ne 0 ]; then \
        usermod -u ${USER_ID} www-data && \
        groupmod -g ${GROUP_ID} www-data; \
    fi

# Créer les répertoires nécessaires avec les bonnes permissions
RUN mkdir -p /var/www/html/var/cache /var/www/html/var/log \
    && chown -R www-data:www-data /var/www/html/var \
    && chmod -R 775 /var/www/html/var

WORKDIR /var/www/html

USER www-data

CMD ["php-fpm"]
