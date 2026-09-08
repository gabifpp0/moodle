#!/bin/sh
set -e

git config --global --add safe.directory /var/www/html

if [ ! -f /var/www/html/vendor/autoload.php ]; then
    composer install --no-dev --classmap-authoritative
fi

if [ "$#" -eq 0 ]; then
    set -- apache2-foreground
fi

exec docker-php-entrypoint "$@"