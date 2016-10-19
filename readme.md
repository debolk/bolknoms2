# Bolknoms
The very best application in the world for feeding your members in an organized and predictable way.

[![Dependency Status](https://www.versioneye.com/user/projects/558f07d031633800240002fa/badge.svg?style=flat)](https://www.versioneye.com/user/projects/558f07d031633800240002fa) [![Code Climate](https://codeclimate.com/github/debolk/bolknoms2/badges/gpa.svg)](https://codeclimate.com/github/debolk/bolknoms2)

## Local installation
This project is locally installed using [vagrant](https://www.vagrantup.com/) and [laravel homestead](http://laravel.com/docs/5.1/homestead). Vagrant provides an super easy way to setup a local virtual machine with the perfect environment for running bolknoms.

1. Install [vagrant](https://www.vagrantup.com/) and [virtualbox](https://www.virtualbox.org/) if not installed already.
1. Clone this repository `git clone git@github.com:debolk/bolknoms2.git`.
1. Install the server-side dependencies using [Composer](https://getcomposer.org/): `php composer.phar install`.
1. Run `vagrant up` in the directory in which you've cloned bolknoms.
1. In the project folder, copy `.env.example` to `.env` and fill in the details you require. Specifically, you must change APP_KEY, OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET. The other values should be fine.
1. Connect to the VM using `vagrant ssh`. Open the project folder (`cd /home/vagrant/bolknoms2`) and run all migrations `php artisan migrate`.
1. Point http://bolknoms.app to the IP address in the `Homestead.yaml` file. On Linux, you usually add the line `192.168.10.10    bolknoms.app` to the `/etc/hosts` file
1. Open [http://bolknoms.app/](http://bolknoms.app/) in your browser.

### Next steps that are probably useful
1. The configuration files has been set up with useful defaults that should work, though you may want to read through `Homestead.yaml` to adapt as needed to your local environment.
1. Opening the administration panel of bolknoms requires board-level permissions. Using Gosa, you can add yourself temporarily(!) to the group "oauth-test". This requires that you have ICTcom-level access or above. Adding your account to this group will grant you access to all OAuth-protected resources regardless of the permission level. Use it for testing on your local machine and remove yourself from the group when done.
1. Meals for the next week can be automatically generated using the command line: `php artisan meals:generate`.

# Testing
Bolknoms has a (limited) test suite consisting mostly of functional integration tests. To run the test suite:

1. Start the Vagrant VM if needed `vagrant up`
1. Create the testing database, named `bolknoms_testing` using a MySQL-client.
1. Run all migrations on the testing database `php artisan migrate --database=mysql_testing`.
1. Login to the VM (`vagrant ssh`) and open the project root directory `cd /vagrant`.
1. Execute `vendor/bin/phpunit` in the project root directory to run the tests.

# Deployment (production)
There is a one-step deployment script `./deploy.sh` which executes the required steps to deploy the application to production, provided that all dependencies are met. This requires the prior installation of PHP, MySQL, npm and other dependencies. It is strongly recommend to follow the initial deployment by hand, and running the one-click script for each deployment afterwards.

## Usage
Create a meal using the administration panel. Anyone can use the front-end interface to subscribe to that meal.

## Architecture
Bolknoms is a MVC-application built on [Laravel](http://laravel.com/), using [zepto.js](http://zeptojs.com/) (a jQuery-light equivalent) for some front-end functionality. It is dependent on some other projects such as [bolklogin](https://auth.debolk.nl/) for authenticating members and [people/blip](https://people.debolk.nl/) for retrieving details of members, etc. [Guzzle](guzzlephp.org) is used for communication with these upstream services.

## Contributing
This project is open for pull requests. Fork the repository and add your own contributions. For significant, non-bugfix contributions, you will be added to the contributors list. Please mind the following conditions:

1. The project follows the [git-flow](http://nvie.com/posts/a-successful-git-branching-model/). Please adhere to the (very basic) standards set. Any new work must be branched of in a feature branch. These branches are prefixed with "feature-", for example "feature-moreswedishchef". Preferrably no underscores.
1. Bolknoms is production software that supports actual business operations at [De Bolk](http://www.debolk.nl). Your changes will be reviewed and tested on a private staging environment, before being deployed to production. Even if your change is perfect, it might not be acceptable for the product. If you want to be sure your change will be accepted, ask in advance. While you are free to argue your case, the decision belongs ultimately to the lead developer (Jakob) and the board of De Bolk.
1. You are strongly urged to add at least a couple of integration tests with your pull request.

## License
Copyright 2011-2016 [Jakob Buis](http://www.jakobbuis.com). This version of Bolknoms is distributed under the GNU GPL v3 license, the full text of which is included in the LICENSE file.
