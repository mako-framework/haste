# Afterburner

[![Static analysis](https://github.com/mako-framework/afterburner/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/mako-framework/afterburner/actions/workflows/static-analysis.yml)

The `mako/afterburner` package allows you to boost application performance by running it on an application server like FrankenPHP.

> Note that this package is experimental!

## Requirements

Mako 11.0 or greater.

## Installation

First you'll need to install the package as a dependency to your project.

```
composer require mako/afterburner
```

Next you'll have to replace the `index.php` contents with the following.

```php
<?php

use app\FrankenPHP;
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

## Docker setup

The following basic dockerfile will help get you started:

```dockerfile
FROM dunglas/frankenphp:1.0.3-php8.3.1

RUN install-php-extensions \
    opcache

COPY app /app/app
COPY public /app/public
COPY vendor /app/vendor

ENV SERVER_NAME="http://"
ENV FRANKENPHP_CONFIG="worker ./public/index.php"
```
