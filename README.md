# Evershop


## _The best place to Buy_

Evershop is a web app that allows you manage a very basic shop. Developed as part of "Software Engineer Admission Test".

## Features

- Test Driven Development (TDD).
- Web Checkout is used as Payment Gateway.
- Generate Orders.
- Show Order Detail.
- Check Order Status.
- Orders List.


## Technologies

[Laravel 8] - Laravel is a web application framework with expressive, elegant syntax.
[MySQL] - MySQL is the world's most popular open source database.
[PHP] - PHP is a popular general-purpose scripting language that is especially suited to web development.
[PHPUnit] - PHPUnit is a programmer-oriented testing framework for PHP.

[Bootstrap] - The world’s most popular framework for building responsive, mobile-first sites.


## Installation

```sh
git clone https://github.com/stalinscj/evershop.git
```

```sh
cd evershop
```

```sh
composer install
```

(If it was not copied automatically after installation):

```sh
cp .env.example .env
```

(If it was not generated automatically after installation):

```sh
php artisan key:generate
```

From MySQL CLI:

```sh
CREATE DATABASE db_database;
```

In .env file set the following variables:

```sh
APP_NAME=
APP_URL=

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

WEB_CHECKOUT_LOGIN=
WEB_CHECKOUT_TRANKEY=
WEB_CHECKOUT_URL=
```

```sh
php artisan migrate
```

```sh
php artisan test
```

```sh
php artisan serve
```

Desde un navegador ingresar a http://127.0.0.1:8000


[//]: # (Links) 

[Laravel 8]: <https://laravel.com>
[MySQL]: <https://www.mysql.com>
[PHP]: <https://www.php.net>
[PHPUnit]: <https://phpunit.de>
[Bootstrap]: <https://getbootstrap.com>
