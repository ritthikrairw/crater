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

    chown -R "$user:$group" ./
    chmod -R 755 ./
    chmod -R 777 ./storage

    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
    php artisan storage:link || true
    php artisan key:generate
    php artisan migrate
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    php artisan optimize --force
fi

exec "$@"
