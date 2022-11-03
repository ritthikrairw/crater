#!/usr/bin/env bash
set -Eeuo pipefail

if [[ "$1" == apache2* ]] || [ "$1" = 'php-fpm' ]; then

    uid="$(id -u)"
    gid="$(id -g)"

    if [ "$uid" = '0' ]; then
        case "$1" in
        apache2*)
            user="${APACHE_RUN_USER:-www-data}"
            group="${APACHE_RUN_GROUP:-www-data}"

            # strip off any '#' symbol ('#1000' is valid syntax for Apache)
            pound='#'
            user="${user#$pound}"
            group="${group#$pound}"
            ;;
        *) # php-fpm
            user='www-data'
            group='www-data'
            ;;
        esac
    else
        user="$uid"
        group="$gid"
    fi

    chown -R "$user:$group" /var/www/html
    chmod -R 755 /var/www/html/storage
    chmod -R 755 /var/www/html/bootstrap/cache

    composer install --no-interaction --prefer-dist --optimize-autoloader
    php artisan storage:link || true
    php artisan key:generate
    php artisan cache:clear
    php artisan route:cache

    # create .env file if not exists and set variables
    if [ ! -f /var/www/html/.env.prod ]; then
        rm -rf /var/www/html/.env.prod
    fi
fi

exec "$@"
