<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'recipe/rsync.php';

set('application', 'Laravel');
set('ssh_multiplexing', true);

set('rsync_src', function () {
    return __DIR__;
});


add('rsync', [
    'exclude' => [
        '.git',
        '/.env',
        '/storage/',
        '/vendor/',
        '/node_modules/',
        '.github',
        'deploy.php',
    ],
]);

task('deploy:secrets', function () {
    file_put_contents(__DIR__ . '/.env', getenv('DOT_ENV'));
    upload('.env', get('deploy_path') . '/shared');
});

//host('myapp.io')
host('13.250.48.167')
  ->hostname('13.250.48.167')
  ->stage('production')
  ->user('root')
  ->set('deploy_path', '/var/www/aklab');

after('deploy:failed', 'deploy:unlock');

desc('Deploy the application');

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'rsync',
    'deploy:secrets',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
    'artisan:migrate',
    'artisan:queue:restart',
    'artisan:cache:clear',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);