<?php
/**
 * An array of commands which are required for to all servers in the 'stor' family
 */

/** @var string $hostname */

return [
    /** Apache and PHP 5.4+ **/
    'yum install httpd php -y',
    'cd /var/www',
    $git_clone,
    'php ' . dirname(dirname(__DIR__)).'/create/vhost/'.basename(__FILE__) . ' "' . $hostname . '"'
];