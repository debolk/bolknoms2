# Bolknoms
The very best application in the world for feeding your members in an organized and predictable way.

## Local installation
This project is locally installed using [vagrant](https://www.vagrantup.com/) and [laravel homestead](http://laravel.com/docs/5.1/homestead). Vagrant provides an super easy way to setup a local virtual machine with the perfect environment for running bolknoms.

1. Install [vagrant](https://www.vagrantup.com/) and [virtualbox](https://www.virtualbox.org/) if not installed already.
1. Clone this repository `git clone git@github.com:debolk/bolknoms2.git`.
1. Install the server-side dependencies using [Composer](https://getcomposer.org/): `php composer.phar install`.
1. Install [node](https://nodejs.org/en/). Install the local dependencies `npm install` and compile all assets by executing the commands in package.json.
1. Run `vagrant up` in the directory in which you've cloned bolknoms.
1. In the project folder, copy `.env.example` to `.env` and fill in the details you require.
1. Connect to the VM using `vagrant ssh`. Open the project folder (`cd /home/vagrant/bolknoms2`) and run all migrations `php artisan migrate` and `php artisan key:generate`.
1. Point http://bolknoms.test to the IP address in the `Homestead.yaml` file. On Linux, you usually add the line `192.168.10.10    bolknoms.test` to the `/etc/hosts` file
1. Open [http://bolknoms.test/](http://bolknoms.test/) in your browser.

### Next steps that are probably useful
1. Opening the administration panel of bolknoms requires board-level permissions. Using Gosa, you can add yourself temporarily(!) to the group "oauth-test". This requires that you have ICTcom-level access or above. Adding your account to this group will grant you access to all OAuth-protected resources regardless of the permission level. Use it for testing on your local machine and remove yourself from the group when done.
1. Meals for the next week can be automatically generated using the command line: `php artisan meals:generate`.

# Deployment (production)
For the initial installation, use the following *rough* process:

1. Install the dependencies: PHP, MySQL, npm and nginx.
1. Install the dependencies by running both `php composer.phar install` and `npm install`.
1. Copy or create the MySQL database.
1. Setup letsencrypt if required

There is a one-step deployment script `./deploy.sh` which executes the required steps to deploy the application to production, provided that all dependencies are met. This script deploys a new version of the software (i.e. pulling master, clearing caches, compiling assets, etc.): it doesn't install everything from scratch. In practice, it is useful for your initial deployment if you run it over and over again, while fixing the errors it spews in every step, until it actually completes without errors.

## Usage
Create a meal using the administration panel. Anyone can use the front-end interface to subscribe to that meal.

## Architecture
Bolknoms is a MVC-application built on [Laravel](http://laravel.com/), using [zepto.js](http://zeptojs.com/) (a jQuery-light equivalent) for some front-end functionality. It is dependent on some other projects such as [bolklogin](https://auth.debolk.nl/) for authenticating members and [people/blip](https://people.debolk.nl/) for retrieving details of members, etc. [Guzzle](guzzlephp.org) is used for communication with these upstream services.

## Contributing
This project is open for pull requests. Fork the repository and add your own contributions. For significant, non-bugfix contributions, you will be added to the contributors list. Please mind the following conditions:

1. The project follows the [git-flow](http://nvie.com/posts/a-successful-git-branching-model/). Please adhere to the (very basic) standards set. Any new work must be branched of in a feature branch. These branches are prefixed with "feature-", for example "feature-moreswedishchef". Preferrably no underscores.
1. Bolknoms is production software that supports actual business operations at [De Bolk](http://www.debolk.nl). Your changes will be reviewed and tested on a private staging environment, before being deployed to production. Even if your change is perfect, it might not be acceptable for the product. If you want to be sure your change will be accepted, ask in advance. The decision belongs ultimately to the lead developer (Jakob) and the board of De Bolk.

## License
Copyright 2011-2018 [Jakob Buis](http://www.jakobbuis.com). This version of Bolknoms is distributed under the GNU GPL v3 license, the full text of which is included in the LICENSE file.
