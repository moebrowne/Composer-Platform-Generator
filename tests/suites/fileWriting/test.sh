#!/usr/bin/env bash
SCRIPTPATH="$( cd "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )"

echo -n "Test that JSON is written correctly"

outputInitial='{
    "name": "test/package",
    "type": "package",
    "description": "This is a \"file writing\" test",
    "keywords": [
        "test",
        "test2"
    ],
    "license": "MIT",
    "support": {
        "email": "test@example.org"
    },
    "require": {
        "php": ">=7.2",
        "ramsey/uuid": "^3.4",
        "ext-json": "*"
    },
    "require-dev": {
        "phpunit/phpunit": "~4.0",
        "orchestra/testbench": "3.3.*",
        "scrutinizer/ocular": "~1.1"
    },
    "prefer-stable": true
}'

outputExpected='{
    "name": "test/package",
    "type": "package",
    "description": "This is a \"file writing\" test",
    "keywords": [
        "test",
        "test2"
    ],
    "license": "MIT",
    "support": {
        "email": "test@example.org"
    },
    "require": {
        "php": ">=7.2",
        "ramsey/uuid": "^3.4",
        "ext-json": "*"
    },
    "require-dev": {
        "phpunit/phpunit": "~4.0",
        "orchestra/testbench": "3.3.*",
        "scrutinizer/ocular": "~1.1"
    },
    "prefer-stable": true,
    "config": {
        "platform": {
            "ext-zip": "1.15.4",
            "ext-zlib": "7.2.31",
            "lib-curl": "7.47.0",
            "php": "7.2.31"
        }
    }
}'

echo "$outputInitial" > "$SCRIPTPATH/composer.json"

export COMPOSER_EXEC="$SCRIPTPATH/composerTest"

php -f ../generator.php "$SCRIPTPATH/composer.json"

outputActual=$(<"$SCRIPTPATH/composer.json")

rm "$SCRIPTPATH/composer.json"

[ "$outputActual" != "$outputExpected" ] && { echo -e "\ndiff: $(diff -C 2 <(echo "$outputActual") <(echo "$outputExpected"))"; exit 1; }

exit 0
