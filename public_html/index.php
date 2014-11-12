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

// foreground colours
$COL_BLACK   = "\x1b[30;01m";
$COL_RED     = "\x1b[31;01m";
$COL_GREEN   = "\x1b[32;01m";
$COL_YELLOW  = "\x1b[33;01m";
$COL_BLUE    = "\x1b[34;01m";
$COL_MAGENTA = "\x1b[35;01m";
$COL_CYAN    = "\x1b[36;01m";
$COL_WHITE   = "\x1b[37;01m";

// background colours
$COL_BG_RED     = "\x1b[41;01m";
$COL_BG_GREEN   = "\x1b[42;01m";
$COL_BG_YELLOW  = "\x1b[43;01m";
$COL_BG_BLUE    = "\x1b[44;01m";
$COL_BG_MAGENTA = "\x1b[45;01m";
$COL_BG_CYAN    = "\x1b[46;01m";
$COL_BG_WHITE   = "\x1b[47;01m";

$COL_RESET = "\x1b[39;49;00m";

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
    $cmds[] = 'yum install git -y';
    $cmds[] = 'cd /tmp';
    $cmds[] = 'rm -Rf server-build';

    // the target machine gets the absolute latest version of code
    $cmds[] = $git_clone;

    $cmds[] = 'echo -e "' . $COL_BLACK . $COL_BG_CYAN . 'Hi ho! Hi ho! It\'s off to work we go! (' . $hostname . ')' . $COL_RESET . '"';

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
}

echo implode(PHP_EOL, $cmds) . PHP_EOL;