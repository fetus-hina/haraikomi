#!/bin/bash

npx updates -u -m
\rm -rf package-lock.json node_modules
npm install

make composer.phar
./composer.phar self-update
./composer.phar update -W
./composer.phar bump
./composer.phar normalize
