<?php

$composerJSONPath = 'path/to/composer.json';

// Get the list of packages in the current platform
exec('composer show -p', $platformLineArray, $returnCode);

$packages = [];

foreach ($platformLineArray as $platformLine) {
    preg_match('/^(?<name>[^ ]+)[ ]+(?<version>[^ ]+)/', $platformLine, $matches);
    $packages[$matches['name']] = $matches['version'];
}

$composerArray = json_decode(file_get_contents($composerJSONPath), true);

// Add the new key to the composer JSON
$composerArray['platform'] = $packages;

file_put_contents($composerJSONPath, json_encode($composerArray));
