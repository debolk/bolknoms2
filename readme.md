# Bolknoms

The very best application in the world for feeding your members in an organized and predictable way.

## Requirements
Bolknoms runs on the Laravel-framework and a SQL-compatible database. Bolknoms has been developed and tested using nginx and MySQl, but you're free to make your own choices. The minimum requirements are:

* PHP >= 5.3.7
* PHP mcrypt extension
* SQL-compatible database
* Something to send e-mails with using the standard PHP `mail()` function

## Installation
1. Clone the repository using git
1. Copy the following configuration files and change as needed
    1. app/config/database.example.php -> app/config/database.php
    1. application/config/app.example.php -> app/config/app.php
1. Install nginx and php5-fpm and configure as needed for Laravel-based applications. An excellent tutorial Linux-based systems is located at [Digital Ocean](https://www.digitalocean.com/community/articles/how-to-install-laravel-with-nginx-on-an-ubuntu-12-04-lts-vps).
1. Install the dependencies using composer by executing `composer install`
1. Create a database and execute all migrations (`php artisan migrate`)
1. The app/storage directory must be writeable by the server.

## Usage
Create a meal using the administration panel. Anyone can use the front-end interface to subscribe to that meal.

## Architecture
Bolknoms is built using [laravel 4](http://laravel.com/).

## Project organisation
The project follows the [GitHub Flow](http://scottchacon.com/2011/08/31/github-flow.html). Please adhere to the (very basic) standards set. Any new work must be branched of in a feature branch. These branches are prefixed with "feature-", for example "feature-moreswedishchef". Preferrably no underscores.

## License
Copyright 2012-2014 [Jakob Buis](http://www.jakobbuis.com). Distributed under the [GNU Lesser General Public License, version 3.0](http://opensource.org/licenses/lgpl-3.0.html).
