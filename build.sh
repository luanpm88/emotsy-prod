#!/bin/bash
if [ -z "$1" ]
then
  echo "Error: please specify the output path" && exit 1
fi

if [ ! -d "$1" ]
then
  echo "Error: directory does not exist" && exit 1
fi

APPNAME="acellemail"
APPVERSION="2.2.0"
APPDIR="$APPNAME-$APPVERSION"
OUTPUT="$1/$APPDIR"

if [ -d "$OUTPUT" ]; then
  echo "Error: director [$OUTPUT] already exists" && exit 1
fi

CURPATH=$(pwd)

cp -r $CURPATH $OUTPUT
cd $OUTPUT

# create version file
touch VERSION
echo "$(git tag --points-at HEAD)" > VERSION

# create default env file
cp .env.example .env

# clean up
rm -fr storage/logs/*
rm -fr storage/jobs/*
rm -fr storage/tmp/*
rm -fr storage/import/*
rm -fr storage/export/*
rm -fr storage/installed
touch storage/recache
rm -fr .authenticate
rm -fr .git*
rm -fr run
rm -fr database/seeds/DatabaseSeeder.php
rm -fr public/source
rm -fr public/thumb
rm -fr build.sh
rm -fr public/upload/templates/*
rm -fr bootstrap/cache/config.php

# copy default lang
rm -fr resources/lang/default
cp -r resources/lang/en resources/lang/default

# run artisan
php artisan config:cache
php artisan key:generate

echo "File exported [$OUTPUT]"

