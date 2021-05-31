# Birthday service

This service provides an information about upcoming people's birthdays and display how much time is left to celebrate today's birthdays.


## Installation

### Installation of MongoDB 
Please follow instructions for you operating system on this page: https://docs.mongodb.com/manual/installation/

### Installation of MongoDB PHP driver
First install driver on your system using pecl. 

```
sudo pecl install mongodb
```
If you are using Windows, please follow this link: https://www.php.net/manual/en/mongodb.installation.windows.php

Finally, add the following line to your php.ini file:
```
extension=mongodb.so
```

### Installation of PHP Composer dependencies
Execute the following command to install all required php composer packages
```
composer install
```

## Run the service
Execute this command from you project root directory
```
php -S localhost:8000 -t public
```

## Run the tests
```
./vendor/bin/phpunit tests
```