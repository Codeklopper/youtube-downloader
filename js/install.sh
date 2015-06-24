#!/bin/bash
 
if [ "$(id -u)" != "0" ]; then
echo "This script must be run as root" 1>&2
exit 1
fi
 
mkdir -p /usr/local/lib/phpunit
mv composer.json /usr/local/lib/phpunit
cd /usr/local/lib/phpunit
 
if [ `which composer` ]; then
composer install
else
if [ ! -f './composer.phar' ]; then
curl -sS https://getcomposer.org/installer | php
fi
php composer.phar install
fi
