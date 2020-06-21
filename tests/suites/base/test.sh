#!/usr/bin/env bash

echo -n "Test that platform is generated correctly"

export COMPOSER_EXEC="$(dirname "${BASH_SOURCE[0]}")/composerTest"

IFS='' outputActual="$(php -f ../generator.php)"

outputExpected='{
    "config": {
        "platform": {
            "ext-zip": "1.15.4",
            "ext-zlib": "7.2.31",
            "lib-curl": "7.47.0",
            "php": "7.2.31"
        }
    }
}'

[ "$outputActual" != "$outputExpected" ] && { echo -e "\ndiff: $(diff -C 2 <(echo "$outputActual") <(echo "$outputExpected"))"; exit 1; }

exit 0
