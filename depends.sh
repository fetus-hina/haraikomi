#!/bin/bash

npx updates -u -m
\rm -rf package-lock.json node_modules
npm install
npm dedupe

make composer.phar
./composer.phar self-update
./composer.phar update
