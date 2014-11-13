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

require dirname(__DIR__) . '/includes/config.php';
require dirname(__DIR__) . '/includes/ansi-colours.php';
require dirname(__DIR__) . '/includes/utils.php';

$cmds = [];
if (empty($_REQUEST['hostname'])) {
    $cmds[] = 'curl ' . $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'])[0] . '?hostname={`hostname`}|sh';
}
elseif (!$hostinfo = parseHostname($_REQUEST['hostname'])) {
    $cmds[] = 'echo -e "' . COL_BLACK . COL_BG_RED . 'Hostname "' . $_REQUEST['hostname'] . '" must be of format AAAANNNN.tier.domain, e.g. http1001.dev.localdomain' . COL_RESET . '"';
}
else {
    // we have an opportunity here to do something specific if we so wished for the host using this service

    // do the absolute minimum here (as this script could easily get out of date on curl'd to server)
    $cmds[] = 'yum update -y';
    $cmds[] = 'yum install git vim php -y';
    $cmds[] = 'cd /tmp';
    $cmds[] = 'rm -Rf server-build';

    // the target machine gets the absolute latest version of code
    $cmds[] = GITHUB_CLONE;

    $cmds[] = 'echo -e "' . COL_BLACK . COL_BG_CYAN . 'Hi ho! Hi ho! It\'s off to work we go! (' . $hostinfo['hostname'] . ')' . COL_RESET . '"';

    $cmds[] = 'php /tmp/server-build/scripts/build.php';
}

echo implode(PHP_EOL, $cmds) . PHP_EOL;