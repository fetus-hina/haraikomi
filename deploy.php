<?php // phpcs:disable

declare(strict_types=1);

namespace Deployer;

require('recipe/yii2-app-basic.php');

set('application', 'haraikomi');
set('repository', 'git@github.com:fetus-hina/haraikomi.git');
set('composer_options', implode(' ', [
    'install',
    '--no-dev',
    '--no-interaction',
    '--no-progress',
    '--no-suggest',
    '--optimize-autoloader',
    '--prefer-dist',
    '--verbose',
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
set('keep_releases', 2);
set('bin/npm', fn () => locateBinaryPath('npm'));
set('bin/make', fn () => locateBinaryPath('make'));

host('2403:3a00:202:1127:49:212:205:127')
    ->user('haraikomi')
    ->stage('production')
    ->roles('app')
    ->set('deploy_path', '~/app');

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:git_config',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'deploy:run_migrations',
    'deploy:build',
    'deploy:npm_prune',
    'deploy:symlink',
    'deploy:clear_opcache',
    'deploy:clear_proxy',
    'deploy:unlock',
    'cleanup',
])->desc('Deploy the project');

task('deploy:git_config', function () {
    run('git config --global advice.detachedHead false');
});

after('deploy:update_code', 'deploy:production');

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
        run('{{bin/make}}');
    });
});

task('deploy:npm_prune', function () {
    within('{{release_path}}', function () {
        run('{{bin/npm}} prune --production');
    });
});

task('deploy:clear_opcache', function () {
    run('curl -f --insecure --resolve haraikomi.fetus.jp:443:127.0.0.1 https://haraikomi.fetus.jp/site/clear-opcache');
});

task('deploy:clear_proxy', function () {
});

after('deploy:failed', 'deploy:unlock');
