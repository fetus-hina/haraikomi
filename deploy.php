<?php

declare(strict_types=1);

namespace Deployer;

require('recipe/yii2-app-basic.php');

set('application', 'haraikomi');
set('repository', 'git@github.com:fetus-hina/haraikomi.git');
set('composer_options', implode(' ', [
    'install',
    '--verbose',
    '--prefer-dist',
    '--no-progress',
    '--no-interaction',
    '--optimize-autoloader',
    '--no-suggest',
]));
set('git_tty', true);
add('shared_files', [
    'config/cookie.php',
]);
add('shared_dirs', [
    'runtime',
]);
add('writable_dirs', [
    'runtime',
    'web/assets',
]);
set('writable_mode', 'chmod');
set('writable_chmod_recursive', false);
set('softwarecollections', []);

set('bin/php', function () {
    if ($scl = get('softwarecollections')) {
        return vsprintf('scl enable %s -- php', [
            implode(' ', array_map(
                'escapeshellarg',
                $scl
            )),
        ]);
    }

    return locateBinaryPath('php');
});

set('bin/npm', function () {
    if ($scl = get('softwarecollections')) {
        return vsprintf('scl enable %s -- npm', [
            implode(' ', array_map(
                'escapeshellarg',
                $scl
            )),
        ]);
    }

    return locateBinaryPath('npm');
});

host('ayanami.single-quote.com')
    ->user('haraikomi')
    ->stage('production')
    ->roles('app')
    ->set('deploy_path', '~/app')
    ->set('softwarecollections', [
        'php74',
        'rh-nodejs14',
    ]);

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:git_config',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:production',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'deploy:run_migrations',
    'deploy:build',
    'deploy:symlink',
    'deploy:clear_opcache',
    'deploy:clear_proxy',
    'deploy:unlock',
    'cleanup',
])->desc('Deploy the project');

task('deploy:git_config', function () {
    run('git config --global advice.detachedHead false');
});

task('deploy:production', function () {
    within('{{release_path}}', function () {
        run('touch .production');
        run('rm -f web/index.test.php');
    });
});

task('deploy:vendors', function () {
    within('{{release_path}}', function () {
        run('{{bin/composer}} {{composer_options}}');
        run('{{bin/npm}} clean-install');
    });
});

task('deploy:build', function () {
    within('{{release_path}}', function () {
        if ($scl = get('softwarecollections')) {
            run(vsprintf('scl enable %s -- make', [
                implode(' ', array_map(
                    'escapeshellarg',
                    $scl
                )),
            ]));
        } else {
            run('make');
        }
    });
});

task('deploy:clear_opcache', function () {
    run('curl -f --insecure --resolve haraikomi.fetus.jp:443:127.0.0.1 https://haraikomi.fetus.jp/site/clear-opcache');
});

task('deploy:clear_proxy', function () {
});

after('deploy:failed', 'deploy:unlock');
