# -------------- BASE ----------------
FROM php:8.1-fpm as base

WORKDIR /app
ENV PHP_AUTOCONF=/usr/bin/autoconf

COPY --chown=www-data:www-data backend .
COPY --from=composer:2.2.0 /usr/bin/composer /usr/bin/composer
COPY build/backend/supervisor.conf /etc/supervisor/conf.d/supervisord.conf

RUN apt-get update && apt-get install -y \
    cron \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    nginx \
    supervisor

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql zip exif pcntl

COPY build/backend/supervisor.conf /etc/supervisor/conf.d/supervisord.conf
COPY build/backend/nginx.conf /etc/nginx/sites-enabled/default
COPY build/backend/nginx.conf /etc/nginx/sites-available/default



# -------------- BUILDER / DEBUG ----------------
FROM base as builder

ARG UID=1000
ARG GID=1000

RUN usermod -u $UID www-data \
    && groupmod -g $GID www-data \
    && usermod -g $GID www-data

# -------------- RUNNER ----------------
FROM builder as runner

CMD ["/usr/bin/supervisord", "-n"]

EXPOSE 80
#HEALTHCHECK --interval=1m CMD curl -f http://localhost/api/ping || exit 1

# -------------- DEV ----------------
FROM builder as dev

CMD ["/usr/bin/supervisord", "-n"]

COPY --chown=www-data:www-data backend .

EXPOSE 80
