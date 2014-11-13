<?php
/**
 * This is a script to build a VM and will be invoked by /index.php - see script that for more info
 */
require dirname(__DIR__) . '/includes/config.php';
require dirname(__DIR__) . '/includes/ansi-colours.php';
require dirname(__DIR__) . '/includes/utils.php';

$hostname = php_uname('n');

if (!$hostinfo = parseHostname($_REQUEST['hostname'])) {
    $cmd = 'echo -e "' . COL_BLACK . COL_BG_RED . 'Hostname "' . $_REQUEST['hostname'] . '" must be of format AAAANNNN.tier.domain, e.g. http1001.dev.localdomain' . COL_RESET . '"';
    passthru($cmd, $cc);
    exit;
}

// Load commands to build settings that are common to all servers in this family
if (file_exists($path = dirname(__DIR__) . ($build = '/builds/' . $hostinfo['family'] . '/' . $hostinfo['family'] . '.php'))) {
    $cmds[] = '# Config from "' . $build . '" loaded';
    $cmds   = array_merge($cmds, require $path);
    runCommands($cmds);
}

// Load commands to build settings that are specific to the server which invoked this script
if (file_exists($path = dirname(__DIR__) . ($build = '/builds/' . $hostinfo['family'] . '/' . $hostinfo['machine'] . '.php'))) {
    $cmds[] = '# Config from "' . $build . '" loaded';
    $cmds   = array_merge($cmds, require $path);
    runCommands($cmds);
}
