#!/bin/bash

npx updates -u -m
\rm -rf package-lock.json node_modules
npm install

make composer.phar
./composer.phar update --with proget-hq/phpstan-yii2:0.7.3
