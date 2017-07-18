#!/bin/bash

# Step 1: list all changed files
# Step 2: move all changed files to patch folder
# Step 2: zip patch folder


if [ -z "$1" ]
then
  echo "Error: please specify the commit hash" && exit 1
fi

if [ -z "$2" ]
then
  echo "Error: please specify the output path" && exit 1
fi

if [ ! -d "$2" ]
then
  echo "Error: directory does not exist" && exit 1
fi

PATCHNAME="patch"
APPNAME="acellemail"
APPDIR="$APPNAME"
APPZIP="$APPNAME-$PATCHNAME-$1.zip"
OUTPUT="$2/$APPDIR"

if [ -d "$OUTPUT" ]; then
  echo "Error: director [$OUTPUT] already exists" && exit 1
fi

# Loop
git diff-tree --no-commit-id --name-only -r "$1" | while read line; do
  install -D "$line" "$OUTPUT/$line"
done

# zip
cd $OUTPUT
cd ..
zip -r "$APPZIP" "$APPDIR"
rm -fr "$APPDIR"
