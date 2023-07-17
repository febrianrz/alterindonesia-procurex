# Alter Indonesia - ProcureX

## Installation

You can install the package via composer:

```bash
composer require alterindonesia/procurex
```

Publish the configuration:

```bash
php artisan vendor:publish --provider="Alterindonesia\Procurex\Providers\AlterindonesiaProcurexProvider"
```
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Requirement
1. PHP >= 8.0
2. Database PostgreSQL12
3. Roadrunner / Swoole (optional)
4. Docker

## Installation
1. ```composer install```
2. setup .env file
3. ```php artisan key:generate```
4. ```php artisan migrate```
5. ```php artisan db:seed```
6. ```php artisan serve```
7. Kunjungi ``http://localhost:8000/admin``
8. Username: ```superadmin@app.com``` Password: ``superadmin``

## Unit Testing

## Build Docker

## CI /CD
