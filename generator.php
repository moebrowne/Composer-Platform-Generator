<?php

// Get the list of packages in the current platform
exec('composer show -p', $platformLineArray, $returnCode);

$packages = [];

foreach ($platformLineArray as $platformLine) {
    preg_match('/^(?<name>[^ ]+)[ ]+(?<version>[^ ]+)/', $platformLine, $matches);
    $packages[$matches['name']] = $matches['version'];
}

var_dump(json_encode($packages));

