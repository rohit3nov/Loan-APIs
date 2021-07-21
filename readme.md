# Loan APIs

## How to Setup
1. Copy `.env.example` to `.env` using `$ cp .env.example .env`
2. Build images and start docker containers in docker folder
`$ cd docker` then

    `$ export LD_LIBRARY_PATH=/usr/local/lib`

    `$ docker-compose build  --build-arg USER_ID=$(id -u) php-fpm`

    `$ docker-compose build  --build-arg USER_ID=$(id -u) database`

    `$ docker-compose up -d`

    Note: If any errors encountered in above commands, please rerun the command

3. Install Dependencies, run laravel and oAuth migrations in php-fpm container.

   Enter container using `$ docker exec -it php-fpm /bin/bash` then

    `$ composer install`

    `$ php artisan migrate`

    `$ php artisan passport:install`

## Paste below link in Postman(`File->Import->Link`) to get APIs
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

## Run test cases
* `$ composer run test`

## Update project via loan:update
* `$ php artisan loan:update`
