<?php
require dirname(__DIR__) . '/includes/config.php';
require dirname(__DIR__) . '/includes/ansi-colours.php';

$hostname = php_uname('n');
$machine  = explode('.', $hostname)[0];
$family   = substr($machine, 0, 4);

$cmds = [];

// Load commands to build settings that are common to all servers in this family
if (file_exists($path = dirname(__DIR__) . ($build = '/builds/' . $family . '/' . $family . '.php'))) {
    $cmds[] = 'echo "Config from ' . $build . ' loaded"';
    $cmds   = array_merge($cmds, require $path);
}

// Load commands to build settings that are specific to the server which invoked this script
if (file_exists($path = dirname(__DIR__) . ($build = '/builds/' . $family . '/' . $machine . '.php'))) {
    $cmds[] = 'echo "Config from ' . $build . ' loaded"';
    $cmds   = array_merge($cmds, require $path);
}

echo PHP_EOL . $COL_YELLOW . implode(PHP_EOL, $cmds) . PHP_EOL . $COL_RESET . PHP_EOL;

foreach ($cmds as $cmd) {
    passthru($cmd, $cc);
}