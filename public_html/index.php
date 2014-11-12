<?php
define('GITHUB_TOKEN', '78abce1f77af5e238a613653eab1b60d7787dbc2');

$cmds = [];
if (empty($_REQUEST['hostname'])) {
    $cmds[] = 'curl ' . $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'])[0] . '?hostname={`hostname`}|sh';
}
else {
    $hostname = $_REQUEST['hostname'];
    $machine  = explode('.', $hostname)[0];
    $family   = substr($machine, 0, 4);

    // do the absolute minimum here (as this script could get out of date on curl'd to server)
    $cmds[] = 'yum update -y';
    $cmds[] = 'yum install git -y';
    $cmds[] = 'cd /tmp';
    $cmds[] = 'rm -Rf server-build';

    // the target machine gets the absolute latest version of code
    $cmds[] = 'git clone https://' . GITHUB_TOKEN . ':x-oauth-basic@github.com/EagleEyeJohn/server-build.git';

    $cmds[] = 'echo "Hi ho! Hi ho! It\'s off to work we go! (' . $hostname . ')"';

    if (file_exists($path = dirname(__DIR__) . '/builds/' . $family . '/' . $family . '.php')) {
        $cmds[] = 'echo "Config from "' . $path . '" loaded';
        $cmds = array_merge($cmds, require $path);
    }

    if (file_exists($path = dirname(__DIR__) . '/builds/' . $family . '/' . $machine . '.php')) {
        $cmds[] = 'echo "Config from "' . $path . '" loaded';
        $cmds = array_merge($cmds, require $path);
    }
}

echo implode(PHP_EOL, $cmds) . PHP_EOL;