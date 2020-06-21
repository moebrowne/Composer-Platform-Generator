#!/usr/bin/env bash

echo -n "Test that the matches are case sensitive"

export COMPOSER_EXEC="$(dirname "${BASH_SOURCE[0]}")/composerTest"

IFS='' outputActual="$(php -f ../generator.php)"

outputExpected='{
    "config": {
        "platform": {
            "ext-CaseSensitive": "7.2.31",
            "ext-Zend-OPcache": "7.2.31",
            "ext-casesensitive": "7.2.31"
        }
    }
}'

[ "$outputActual" != "$outputExpected" ] && { echo -e "\ndiff: $(diff -C 2 <(echo "$outputActual") <(echo "$outputExpected"))"; exit 1; }

exit 0
