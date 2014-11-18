#!/bin/bash

# change to the directory that this script is in ../.git/hooks
MY_PATH="`dirname \"$0\"`"

# jump up out of ../.git/hooks
cd "$MY_PATH/../.."

MY_PROJ="`basename \"$PWD\"`"

pwd

# if we haven't yet created a symlink to the live composer.json, do so
if [ ! -e composer.json ] && [ -f config/composer/$APP_ENV.json ]; then
    ln -s config/composer/$APP_ENV.json composer.json
fi


# only run composer if this is a composer enabled site
if [ -e composer.json ]; then
# live statements comments
#    MY_COMPOSER="/var/log/httpd/${MY_PROJ}/composer_log"

#    touch "$MY_COMPOSER"
#    /usr/local/bin/composer --no-ansi selfupdate   2>&1 >> "$MY_COMPOSER"
#    /usr/local/bin/composer --no-ansi -o -v update 2>&1 >> "$MY_COMPOSER"
    /usr/local/bin/composer selfupdate
    /usr/local/bin/composer update -v -o
fi
