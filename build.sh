#!/bin/bash

# Step 1: make sure you are at the correct tag/version
# Step 2: make sure you run php "composer.phar install" and "composer.phar dump-autoload" without error
# Step 2: configure the patch.rb file


if [ -z "$1" ]
then
  echo "Error: please specify the output path" && exit 1
fi

if [ ! -d "$1" ]
then
  echo "Error: directory does not exist" && exit 1
fi

APPNAME="acellemail"
VERSION=$(git tag --points-at HEAD)
# VERSION=${VERSION:-"-latest"}

if [ -z "$VERSION" ]
then
  echo "Error: no version (tag) found" && exit 1
fi

APPDIR="$APPNAME"
APPZIP="$APPNAME-$VERSION.zip"
OUTPUT="$1/$APPDIR"
CURDIR=$(pwd)

if [ -d "$OUTPUT" ]; then
  echo "Error: director [$OUTPUT] already exists" && exit 1
fi

CURPATH=$(pwd)

cp -r $CURPATH $OUTPUT
cd $OUTPUT

# create version file
touch VERSION
echo "$VERSION" > VERSION

# create default env file
cp .env.example .env

# clean up
rm -fr storage/logs/*
rm -fr storage/jobs/*
rm -fr storage/tmp/*
rm -fr storage/import/*
rm -fr storage/export/*
rm -fr storage/framework/views/*
rm -fr storage/framework/cache/*
rm -fr storage/framework/sessions/*
rm -fr storage/installed
touch storage/recache
touch storage/logs/mail.log
rm -fr .authenticate
rm -fr .git*
rm -fr database/seeds/DatabaseSeeder.php
rm -fr public/source
rm -fr public/thumb
rm -fr build.sh
rm -fr public/upload/templates/*
rm -fr bootstrap/cache/config.php
rm -fr php-cs-fixer
rm -fr composer.phar
# rm -fr .env.example  # keep to merge
rm -fr doc
rm -fr reset
rm -fr apigen.phar

# Reduce file size
rm -fr vendor/paypal/rest-api-sdk-php/sample
rm -fr vendor/paypal/rest-api-sdk-php/tests

# copy default lang
rm -fr resources/lang/default
cp -r resources/lang/en resources/lang/default

# generate unique key
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan key:generate

# Generate the upgrade patch file
ruby patch.rb /tmp/tmpbuild "$CURDIR" "$VERSION"
mkdir upgrade
test -e /tmp/tmpbuild && mv /tmp/tmpbuild/* upgrade/
test -e /tmp/tmpbuild && rm -fr /tmp/tmpbuild
rm -fr patch.rb

cd ..
chmod 755 -R "$APPDIR"
chmod 777 -R "$APPDIR/storage"
chmod 777 -R "$APPDIR/bootstrap/cache"
zip -r "$APPZIP" "$APPDIR" > /dev/null
rm -fr "$APPDIR"

echo "File exported [$APPZIP]"
