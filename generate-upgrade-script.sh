#!/bin/bash

if [ -z "$1" ]
then
  TO=$(git rev-parse HEAD)
else
  TO=$1
fi

FROM="v2.2.0-p1"
OUT="./upgrade.sh"

cat > $OUT <<- EOS
#!/bin/bash

# UPGRADE
# * FROM : "v2.2.0*"
# * TO   : "$TO"

if [ -z "\$1" ]
then
  echo "Error: please specify the target path" && exit 1
fi

if [ ! -d "\$1" ]
then
  echo "Error: directory does not exist" && exit 1
fi

echo "* Replacing files"
EOS

git diff "$FROM" "$TO" --name-only | sort | xargs -I {} echo "cp ./{} \$1/{}" >> $OUT

cat >> $OUT <<- EOS

cd "\$1"

echo "* Upgrading packages"
# rm -fr ./vendor
php composer.phar install

echo "* Migrating database"
php artisan config:cache
php artisan migrate

echo "Finished!"
EOS

chmod +x $OUT
