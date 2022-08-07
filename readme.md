# Bolknoms
The very best application in the world for feeding your members in an organized and predictable way.

## Local installation
Local installation is done as with any plain Laravel project. You can use [Homestead](https://laravel.com/docs/9.x/homestead), [Laravel Valet](https://laravel.com/docs/9.x/valet) or [Laravel Sail](https://laravel.com/docs/9.x/sail) to run the project. If you have no existing setup, Sail is recommended.

### Next steps that are probably useful
1. Opening the administration panel of bolknoms requires board-level permissions. Using Gosa, you can add yourself temporarily(!) to the group "oauth-test". This requires that you have ICTcom-level access or above. Adding your account to this group will grant you access to all OAuth-protected resources regardless of the permission level. Use it for testing on your local machine and remove yourself from the group when done.
1. Meals for the next week can be automatically generated using the command line: `php artisan meals:generate`.

# Deployment (production)
For the initial installation, use the following *rough* process:

1. Install the server-dependencies: PHP, MySQL/MariaDB, NodeJS, NGINX, and Composer.
1. Install required PHP modules: curl, mbstring, mysql, xml and zip.
1. Install the dependencies by running both `composer install` and `npm install`.
1. Copy or create the MySQL database.
1. Setup letsencrypt if required

Regular deployment is done using [deployer](https://deployer.org/).

## Usage
Create a meal using the administration panel. Anyone can use the front-end interface to subscribe to that meal.

## Architecture
Bolknoms is a MVC-application built on [Laravel](http://laravel.com/), using [zepto.js](http://zeptojs.com/) (a jQuery-light equivalent) for some front-end functionality. It is dependent on some other projects such as [bolklogin](https://auth.debolk.nl/) for authenticating members and [people/blip](https://people.debolk.nl/) for retrieving details of members, etc. [Guzzle](guzzlephp.org) is used for communication with these upstream services.

## Contributing
This project is open for pull requests. Fork the repository and add your own contributions.

Bolknoms is production software that supports actual business operations at [De Bolk](http://www.debolk.nl). Your changes will be reviewed and tested on a private staging environment, before being deployed to production. Even if your change is perfect, it might not be acceptable for the product. If you want to be sure your change will be accepted, ask in advance. The decision belongs ultimately to the lead developer (Jakob) and the board of De Bolk.

## License
Copyright 2011-2022 [Jakob Buis](http://www.jakobbuis.com). This version of Bolknoms is distributed under the GNU GPL v3 license, the full text of which is included in the LICENSE file.
