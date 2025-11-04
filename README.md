# README #

This README would normally document whatever steps are necessary to get your application up and running.

### What is this repository for? ###

* Quick summary
* Version 0.1

# 🛠 How do I get set up? #

## PHP Version
PHP >= 8.2

## Laravel Version
Laravel 12

## Design system
 * Tailwind CSS 3.1.0
 * Alpine.js 3.4.2
 * Grid.js 6.2.0

## Database Version
mysql >= 8.0

## APACHE Version
Apache >= 2.4.62

## Node js Version
Node js >= 22.19
## Configuration
    cp .env.example .env
## Dependencies
    composer install
    npm install
## Database configuration
* create database with the name jobscaning
```http
php artisan migrate:fresh --seed
php artisan key:generate
```
## How to run the application for development
    npm run dev
    enter to http://*carpeta*.test in laragon
## How to run tests
    npm run build

## Credentials ##
    
#### Email
    contacto@benthocode.com
#### Password
    admin123

## Possible errors
- In excel.php line 131:

  Class "PhpOffice\PhpSpreadsheet\Reader\Csv" not found


Script @php artisan package:discover --ansi handling the post-autoload-dump event returned with error code 1
ed autoload files

```http
rm -rf vendor composer.lock
composer clearcache
composer install
```


## 🚀 Deployment instructions
