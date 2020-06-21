#!/usr/bin/env php
<?php

$composerPath = getenv('COMPOSER_EXEC') ?: 'composer';

$packageBlackList = json_decode(file_get_contents(__DIR__ . '/package-blacklist.json'));

$composerJSONPath = $argc === 2 ? $argv[1]:null;

// Get the list of packages in the current platform
exec($composerPath . ' show -p', $platformLineArray, $returnCode);

if ($returnCode !== 0) {
    throw new Exception('Failed to run Composer, is it installed?');
}

$packages = [];

foreach ($platformLineArray as $platformLine) {
    preg_match('/^(?<name>[^ ]+)[ ]+(?<version>[^ ]+)[^(]+(|(\(actual version: [^)]+\))|(Package overridden via config\.platform \((?:(?:same as actual)|(?:actual: (?<versionActual>[^)]+)))\)))$/', $platformLine, $matches);

    // Check if this package is blacklisted
    if (in_array($matches['name'], $packageBlackList)) {
        continue;
    }

    if (array_key_exists('versionActual', $matches)) {
        $version = $matches['versionActual'];
    } else {
        $version = $matches['version'];
    }

    $packages[$matches['name']] = $version;
}

// Sort the packages for readability
ksort($packages, SORT_NATURAL);

if ($composerJSONPath === null) {
    $composerArray = [
        'config' => [
            'platform' => $packages,
        ],
    ];

    echo json_encode($composerArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    echo PHP_EOL;

    exit;
}

if (file_exists($composerJSONPath) === false) {
    throw new Exception('Unable to locate composer.json');
}

if (is_writable($composerJSONPath) === false) {
    throw new Exception('composer.json is not writable');
}

$composerArray = json_decode(file_get_contents($composerJSONPath), true);

// Add the new key to the composer JSON
if (array_key_exists('config', $composerArray) === false) {
    $composerArray['config'] = [];
}

$composerArray['config']['platform'] = $packages;

file_put_contents($composerJSONPath, json_encode($composerArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
