<?php
/**
 * An array of commands which are required for to all servers in the 'stor' family
 */

require dirname(dirname(__DIR__)).'/create/vhost/'.basename(__FILE__);  // create the vhost

return [
    /** Apache and PHP 5.4+ **/
    'yum install httpd php -y',
    'cd /var/www',
    'rm -Rf server-build',
    GITHUB_CLONE
];