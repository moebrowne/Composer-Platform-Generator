<?php

$packageBlackList = json_decode(file_get_contents('package-blacklist.json'));

$composerJSONPath = $argc === 1 ? $argv[1]:null;

// Get the list of packages in the current platform
exec('composer show -p', $platformLineArray, $returnCode);

if ($returnCode !== 0) {
    throw new Exception('Failed to run Composer, is it installed?');
}

$packages = [];

foreach ($platformLineArray as $platformLine) {
    preg_match('/^(?<name>[^ ]+)[ ]+(?<version>[^ ]+)/', $platformLine, $matches);

    // Check if this package is blacklisted
    if (in_array($matches['name'], $packageBlackList)) {
        continue;
    }

    $packages[$matches['name']] = $matches['version'];
}

// Sort the packages for readability
ksort($packages, SORT_NATURAL);

if ($composerJSONPath === null) {
    $composerArray = [];
    $composerArray['platform'] = $packages;
    echo json_encode($composerArray, JSON_PRETTY_PRINT);

    exit;
}

$composerArray = json_decode(file_get_contents($composerJSONPath), true);

// Add the new key to the composer JSON
$composerArray['platform'] = $packages;

file_put_contents($composerJSONPath, json_encode($composerArray, JSON_PRETTY_PRINT));
