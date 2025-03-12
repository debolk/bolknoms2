<?php

namespace Deployer;

require 'recipe/laravel.php';

set('application', 'bolknoms2');
set('repository', 'git@github.com:debolk/bolknoms2.git');
set('default_stage', 'production');
set('keep_releases', 5);

host('noms.debolk.nl')
    ->set('hostname', '10.99.1.24')
    ->set('remote_user', 'jakob')
    ->set('branch', 'main')
    ->set('deploy_path', '/srv/bolknoms2');

// Build frontend upon release
task('build:frontend', function () {
    within('{{release_path}}', function () {
        run('npm install');
        run('npm run build');
    });
});
before('deploy:publish', 'build:frontend');

// Always migrate on deployment
before('deploy:symlink', 'artisan:migrate');

// Verify the build locally before starting
task('build:verify', function () {
    runLocally('vendor/bin/sail test');
    runLocally('vendor/bin/phpstan');
});
after('deploy:setup', 'build:verify');
