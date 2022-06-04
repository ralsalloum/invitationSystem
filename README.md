# Symfony 6.0.9 App
## PHP 8.0
****************************************************
*. env file and private-public keys not enclosed .*
## Project setup

### First, run following command to install necessary dependencies
```
composer update
```
### Second, creat the database
1) add to** .env** file correct connection string
   `DATABASE_URL=mysql://root@127.0.0.1:3306/invitationSystemDB?serverVersion=8`

2) create database
```
php bin/console doctrine:database:create
```

3) make migration
```
php bin/console make:migration
```

4) run migration versions to create tables
```
php bin/console doctrine:migration:migrate
```
***

### For testing
1) use the test database in _.env.test_
   `mysql://root@127.0.0.1:3306/invitationSystemDB_test?serverVersion=8&charset=utf8mb4`
   

2) Add fixtures data which will be used for testing
   
   _Note: adding fixtures with purged option_
   `php bin/console doctrine:fixtures:load --env=test`
   

3) Now can run the test
   
   `php bin/phpunit`