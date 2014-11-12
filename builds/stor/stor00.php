<?php
/**
 * An array of commands which are required to build server 'stor00' only
 */

$port = 80;

/** @var string $hostname */
/** @var string $machine */

$vhost = <<<VHOST
<VirtualHost *:$port>
    DocumentRoot /var/www/server-build
    ServerName $hostname

    # Other directives here
</VirtualHost>
VHOST;

file_put_contents('/etc/httpd/conf.d/vhost-' . strtolower($machine), $vhost);

return [
];