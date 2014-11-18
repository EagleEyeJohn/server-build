<?php
/**
 * This is a script to build a VM and will be invoked by /index.php - see script that for more info
 */
require dirname(__DIR__) . '/includes/config.php';
require dirname(__DIR__) . '/includes/ansi-colours.php';
require dirname(__DIR__) . '/includes/utils.php';

$hostname = php_uname('n');

if (!$hostinfo = parseHostname($hostname)) {
    $cmd = 'echo -e "' . COL_BLACK . COL_BG_RED . 'Hostname "' . $hostname . '" must be of format AAAANNNN.tier.domain, e.g. http1001.dev.localdomain' . COL_RESET . '"';
    passthru($cmd, $cc);
    exit;
}

// Load commands to build settings that are common to all servers in this family
if (file_exists($path = dirname(__DIR__) . ($build = '/builds/' . $hostinfo['family'] . '/' . $hostinfo['family'] . '.php'))) {
    $cmds   = [];
    $cmds[] = '# Config from "' . $build . '" loaded';
    $cmds   = array_merge($cmds, require $path);
    runCommands($cmds);
}

// Load commands to build settings that are specific to the cluster of the machine which invoked this script
if (file_exists($path = dirname(__DIR__) . ($build = '/builds/' . $hostinfo['family'] . '/' . $hostinfo['cluster'] . '.php'))) {
    $cmds   = [];
    $cmds[] = '# Config from "' . $build . '" loaded';
    $cmds   = array_merge($cmds, require $path);
    runCommands($cmds);
}

// Load commands to build settings that are specific to the server which invoked this script
if (file_exists($path = dirname(__DIR__) . ($build = '/builds/' . $hostinfo['family'] . '/' . $hostinfo['machine'] . '.php'))) {
    $cmds   = [];
    $cmds[] = '# Config from "' . $build . '" loaded';
    $cmds   = array_merge($cmds, require $path);
    runCommands($cmds);
}

// Load commands to build SSH certificate login details for Eagle Eye staff
$cmds = [];
try {
    $dir  = new DirectoryIterator(dirname(__DIR__) . '/ssh-keys');
    foreach ($dir as $file_info) {
        if (!$file_info->isDot()) {
            $user   = basename($file_info->getFilename(), '.pub');
            $cmds[] = 'useradd ' . $user;
            $cmds[] = 'mkdir ' . ($path = '/home/' . $user . '/.ssh');
            $cmds[] = 'chmod 0700 ' . $path;
            $cmds[] = 'cat ' . escapeshellarg($file_info->getPathname()) . ' > ' . ($path = '/home/' . $user . '/.ssh/authorized_keys');
            $cmds[] = 'chmod 0600 ' . $path;
        }
    }
    runCommands($cmds);
}
catch (\Exception $e) {
    $cmds[] = 'echo -e "' . COL_BLACK . COL_BG_RED . 'SSH KEY ERROR : "' .$e->getMessage() . COL_RESET . '"';
}

$cmds = [
    'hostname',
    'ip addr | grep "inet " | awk "{ print $2; }" | sed "s/\/.*$//"',
];
runCommands($cmds);
