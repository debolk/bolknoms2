# Bolknoms

[![Dependency Status](https://www.versioneye.com/user/projects/5502b68b4a10640f8c000184/badge.svg?style=flat)](https://www.versioneye.com/user/projects/5502b68b4a10640f8c000184)

The very best application in the world for feeding your members in an organized and predictable way.

## Requirements
Bolknoms runs on the Laravel-framework and a SQL-compatible database. Bolknoms has been developed and tested using nginx and MySQl, but you're free to make your own choices. The minimum requirements are:

* PHP >= 5.3.7
* PHP mcrypt extension
* SQL-compatible database
* Something to send e-mails with using the standard PHP `mail()` function

## Installation
1. Clone the repository using git. 
1. Install nginx and php5-fpm and configure as needed for Laravel-based applications. An excellent tutorial Linux-based systems is located at [Digital Ocean](https://www.digitalocean.com/community/articles/how-to-install-laravel-with-nginx-on-an-ubuntu-12-04-lts-vps).
1. Install two extensions needed for bolknoms: php5-curl and php5-memcached. 
1. Copy `.env.development.php.example` to `.env.development.php` and adapt as needed.
1. The app/storage directory must be writeable by the server, usually `chmod g+w -R app/storage`. 
1. Install the dependencies using composer by executing `composer install`. 
1. Create a database and execute all migrations (`php artisan migrate`). 

## Usage
Create a meal using the administration panel. Anyone can use the front-end interface to subscribe to that meal.

## Architecture
Bolknoms is built using [laravel 5](http://laravel.com/).

## Project organisation
The project follows the [git-flow](http://nvie.com/posts/a-successful-git-branching-model/). Please adhere to the (very basic) standards set. Any new work must be branched of in a feature branch. These branches are prefixed with "feature-", for example "feature-moreswedishchef". Preferrably no underscores.

## License
Copyright 2012-2015 [Jakob Buis](http://www.jakobbuis.com).