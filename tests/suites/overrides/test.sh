#!/usr/bin/env bash

echo -n "Test that platform overrides are respected"

export COMPOSER_EXEC="$(dirname "${BASH_SOURCE[0]}")/composerTest"

IFS='' outputActual="$(php -f ../generator.php)"

outputExpected='{
    "config": {
        "platform": {
            "ext-Zend-OPcache": "7.2.31",
            "ext-zip": "1.15.4",
            "ext-zlib": "7.2.31",
            "lib-libxml": "2.9.10",
            "lib-openssl": "1.1.1.7",
            "php": "7.2.31"
        }
    }
}'

[ "$outputActual" != "$outputExpected" ] && { echo -e "\ndiff: $(diff -C 2 <(echo "$outputActual") <(echo "$outputExpected"))"; exit 1; }

exit 0
