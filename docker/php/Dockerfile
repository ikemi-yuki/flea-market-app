FROM php:8.1-fpm

COPY php.ini /usr/local/etc/php/

RUN apt-get update \
  && apt-get install -y default-mysql-client zlib1g-dev libzip-dev unzip \
  curl \
  chromium \
  chromium-driver \
  fonts-ipafont-gothic \
  fonts-liberation \
  libnss3 \
  libatk-bridge2.0-0 \
  libgtk-3-0 \
  libasound2 \
  libx11-xcb1 \
  libxcomposite1 \
  libxrandr2 \
  libxdamage1 \
  libgbm1 \
  libpango-1.0-0 \
  libpangocairo-1.0-0 \
  libxshmfence1 \
  xdg-utils \
  && docker-php-ext-install pdo_mysql zip \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

WORKDIR /var/www