# Loan APIs

## How to Setup
1. Copy `.env.example` to `.env` using `$ cp .env.example .env`
2. Build images and start docker containers  using `$ sh setup.sh`

    <i>Note: Above step would take little longer. If any errors encountered in above step, please rerun the setup file</i>

3. Install Dependencies, run laravel and oAuth migrations in php-fpm container.

   Enter container using `$ docker exec -it php-fpm /bin/bash` then

    `$ composer install`

    `$ php artisan migrate`

    `$ php artisan passport:install`

    `$ exit`

## Postman API Collection
Import below link in Postman(`File->Import->Link`) to get APIs

`https://www.getpostman.com/collections/5d38a316320c9e53c0f7`


## API Details

User
- Register User
- Get Token

Loan
- Create Loan
- Get Loan Details
- Approve/Reject Loan
- Get User Loans

Payments
- Get Repayment Details
- Pay Repayment

## phpmyadmin creds

    url     : http://localhost:3307/
    usename : root
    password: root_pass

## Run test cases inside php-fpm container

Enter container using `$ docker exec -it php-fpm /bin/bash` then

`$ composer run test`
