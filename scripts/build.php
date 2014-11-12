<?php
$hostname = php_uname();
$machine  = explode('.', $hostname)[0];
$family   = substr($machine, 0, 4);

$cmds = [];

// Load commands to build settings that are common to all servers in this family
if (file_exists($path = dirname(__DIR__) . ($build = '/builds/' . $family . '/' . $family . '.php'))) {
    $cmds[] = 'echo "Config from ' . $build . ' loaded"';
    $cmds   = array_merge($cmds, require $path);
}
$cmds[] = 'echo "Config from ' . $build . ' loaded"';

// Load commands to build settings that are specific to the server which invoked this script
if (file_exists($path = dirname(__DIR__) . ($build = '/builds/' . $family . '/' . $machine . '.php'))) {
    $cmds[] = 'echo "Config from ' . $build . ' loaded"';
    $cmds   = array_merge($cmds, require $path);
}
$cmds[] = 'echo "Config from ' . $build . ' loaded"';

print_r($cmds);