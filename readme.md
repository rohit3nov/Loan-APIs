# Loan APIs

## How to Setup
1. Install Dependencies using composer `$ composer install`
2. Build images and start docker containers in docker folder
`$ cd docker` then

    `$ docker-compose build  --build-arg USER_ID=$(id -u) php-fpm`

    `$ docker-compose build  --build-arg USER_ID=$(id -u) database`

    `$ docker-compose up -d`

3. Run Laravel and passport migrations in php-fpm container.

   Enter container using `$ docker exec -it php-fpm /bin/bash` then

    `$ php artisan migrate`

    `$ php artisan passport:install`

## Postman API collection link
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

## Run test cases
* `$ composer run test`

## Update project via loan:update
* `$ php artisan loan:update`
