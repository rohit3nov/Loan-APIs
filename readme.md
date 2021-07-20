# Loan APIs

## Features

* Laravel 5.7
* Separate modules
* API
* Laravel oAuth
* Hash base authentication once
* Unit test
* Laravel passport
* Laravel custom artisan command
* Laravel queue jobs

## Contributors

* Author : Rohit Pandita <rohit3nov@gmail.com>

## Install Dependencies for Development
* `$ composer install`

## Install Dependencies for Production
* `$ composer install --no-dev`

## Migrate Database for very first time (after create database time)
attention: this will remove everything in database
* `$ php artisan migrate:fresh --seed`

## Run test cases
* `$ composer run test`

## Update project via loan:update
* `$ php artisan loan:update`

## Configuration

All required environment variables can be found in .env.example

## Docker

cd docker
run docker-compose up -d

`if you face any 'OPEN SSL ISSUE' while building php-fpm, type "export LD_LIBRARY_PATH=/usr/local/lib" and trying docker compose again`

## APIs

