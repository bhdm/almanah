#!/bin/bash

CURRENT_DIR=/var/www/euat
git pull
php ././../composer.phar install
php bin/console assets:install --symlink
php bin/console assetic:dump --env=prod
php bin/console doctrine:schema:update --force
rm -R var/cache/*
rm -R var/logs/*
echo "--------------------------------------------------------------------------"
echo "Installation complete"
echo "--------------------------------------------------------------------------"
