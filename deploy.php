<?php

namespace Deployer;

require 'recipe/laravel.php';

set('application', 'bolknoms2');
set('repository', 'git@github.com:debolk/bolknoms2.git');
set('default_stage', 'production');
set('keep_releases', 5);

host('noms.debolk.nl')
    ->setRemoteUser('jakob')
    ->setHostname('10.99.1.24')
    ->setDeployPath('/srv/bolknoms2')
    ->setForwardAgent(true);

// Build frontend upon release
task('build:frontend', function () {
    within('{{release_path}}', function () {
        run('npm install');
        run('npm run production');
    });
});
before('deploy:publish', 'build:frontend');

// Always migrate on deployment
before('deploy:symlink', 'artisan:migrate');
