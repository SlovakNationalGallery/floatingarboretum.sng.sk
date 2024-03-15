<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github.com:SlovakNationalGallery/floatingarboretum.sng.sk.git');
set('bin/npm', 'n exec 20 npm');
set('bin/php', 'php8.2');
set('bin/composer', '{{bin/php}} {{deploy_path}}/.dep/composer.phar');

add('shared_files', []);
add('shared_dirs', ['resources/fonts','public/audio']);
add('writable_dirs', []);

// Hosts

host('webumenia.sk')
    ->set('remote_user', 'lab_sng')
    ->set('deploy_path', '/var/www/floatingarboretum.sng.sk');

// Tasks

task('build', function () {
    cd('{{release_path}}');
    run('{{bin/npm}} ci');
    run('{{bin/npm}} run build');
});

// Hooks

after('deploy:vendors', 'build');
after('deploy:failed', 'deploy:unlock');

