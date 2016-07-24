# Dev test / Task list

[TOC]

## Description
The project it is a small API service to deal with a simple tasking list.

## Requirements
To install, run and test this application you will need:
- PHP >= 5.5.9
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- MySQL 

## Instalation
To install the development dependencies run the following on project repository:
```bash
composer install
cp .env.example .env
# then... do the database setup at .env file
php artisan migrate
php artisan db:seed # if you want some fake data
```

## Running
To put the server on running mode run:
```bash
cd public
php -S 0.0.0.0:8000
# the api will be at http://localhost:8000
```

## Testing
To run the tests, the server don't need to be up, just run the following:
```bash
bin/phpunit
```

## Conclusion
The project was executed with no problems, and was applied nice concepts as:
- Laravel Lumen as micro service framework;
- REST API with DingoAPI;
- PhpUnit testing;
- UUID as primary identifier of Task entity;
- Internationalization ready to use;
