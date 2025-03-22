# Haste

[![Static analysis](https://github.com/mako-framework/haste/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/mako-framework/haste/actions/workflows/static-analysis.yml)

The `mako/haste` package allows you to boost your application performance by running it on an application server like [FrankenPHP](https://frankenphp.dev/).

The performance gains will vary based on the application but a basic "Hello, world!" application will run about 4-5 times faster than on a php+apache setup.

> Note that this package is experimental and make sure not to leak data between requests by using static variables!

## Requirements

Mako 11.0 or greater.

## Installation

First you'll need to install the package as a dependency to your project.

```
composer require mako/haste
```

Next you'll have to replace the `index.php` contents with the following.

```php
<?php

use mako\haste\FrankenPHP;
use mako\application\web\Application;

/**
 * Include the application init file.
 */
include dirname(__DIR__) . '/app/init.php';

/*
 * Start and run the application.
 */
FrankenPHP::run(new Application(MAKO_APPLICATION_PATH));
```

Finally, you should disable auto-registration of the error handler by setting `error_handler.register` to `false` in `app/config/application.php`.

That's it! Enjoy your (hopefully) improved performance 🎉

## Docker setup

The following basic dockerfile will help to get you started:

```dockerfile
FROM dunglas/frankenphp:1.4.4-php8.4

ARG USER=haste

RUN install-php-extensions \
    opcache \
	pdo_mysql

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY . /app
#COPY php-overrides.ini /usr/local/etc/php/conf.d/.

RUN useradd ${USER}
RUN setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp
RUN chown -R ${USER}:${USER} /data/caddy && chown -R ${USER}:${USER} /config/caddy
RUN chown -R ${USER}:${USER} ./app/storage

USER ${USER}

ENV SERVER_NAME=:80
ENV FRANKENPHP_CONFIG="worker ./public/index.php"
```
