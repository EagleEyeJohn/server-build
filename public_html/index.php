<?php
/**
 * This is a bootstrap to enable the scripted build of a VM
 *
 * from the empty CentOS VM, when logged in as root, issue the following command to begin the build
 *
 *      curl <server>/server-build/ | sh           e.g. curl 10.72.4.27/server-build/ | sh
 *
 * where <server> is the IP/hostname of a server which hosts this project, and is accessible at /server-build/
 */
define('GITHUB_TOKEN', '78abce1f77af5e238a613653eab1b60d7787dbc2');

require dirname(__DIR__) . '/includes/ansi-colours.php';

$git_clone = 'git clone https://' . GITHUB_TOKEN . ':x-oauth-basic@github.com/EagleEyeJohn/server-build.git';

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
    $cmds[] = 'yum install git vim -y';
    $cmds[] = 'cd /tmp';
    $cmds[] = 'rm -Rf server-build';

    // the target machine gets the absolute latest version of code
    $cmds[] = $git_clone;

    $cmds[] = 'echo -e "' . $COL_BLACK . $COL_BG_CYAN . 'Hi ho! Hi ho! It\'s off to work we go! (' . $hostname . ')' . $COL_RESET . '"';

    $cmds[] = 'php /tmp/server-build/scripts/build.php';
}

echo implode(PHP_EOL, $cmds) . PHP_EOL;