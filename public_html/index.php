<?php
define('GITHUB_TOKEN', '78abce1f77af5e238a613653eab1b60d7787dbc2');

$cmds = [];
if (empty($_REQUEST['hostname'])) {
    $cmds[] = 'curl ' . $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'])[0] . '?hostname={`hostname`}|sh';
}
else {
    $hostname = $_REQUEST['hostname'];

    $cmds[] = 'yum update -y';
    $cmds[] = 'yum install git net-tools -y';
    $cmds[] = 'cd /tmp';
    $cmds[] = 'rm -Rf server-build';
    $cmds[] = 'git clone https://' . GITHUB_TOKEN . ':x-oauth-basic@github.com/EagleEyeJohn/server-build.git';

    $cmds[] = 'echo "Hi ho! Hi ho! It\'s off to work we go! ('. $hostname . ')"';
}

echo implode(PHP_EOL, $cmds) . PHP_EOL;