<?php
require dirname(__DIR__) . '/includes/config.php';
require dirname(__DIR__) . '/includes/ansi-colours.php';

$hostname = php_uname('n');
$machine  = explode('.', $hostname)[0];
$family   = substr($machine, 0, 4);

function runCommands($cmds) {
    global $COL_YELLOW, $COL_RESET;

    foreach ($cmds as $cmd) {
        echo PHP_EOL . $COL_YELLOW . $cmd . PHP_EOL . $COL_RESET;
        passthru($cmd, $cc);
    }
}

// Load commands to build settings that are common to all servers in this family
if (file_exists($path = dirname(__DIR__) . ($build = '/builds/' . $family . '/' . $family . '.php'))) {
    $cmds[] = '# Config from "' . $build . '" loaded';
    $cmds   = array_merge($cmds, require $path);
    runCommands($cmds);
}

// Load commands to build settings that are specific to the server which invoked this script
if (file_exists($path = dirname(__DIR__) . ($build = '/builds/' . $family . '/' . $machine . '.php'))) {
    $cmds[] = '# Config from "' . $build . '" loaded';
    $cmds   = array_merge($cmds, require $path);
    runCommands($cmds);
}
