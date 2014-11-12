<?php
$port     = 80;

$vhost = <<<VHOST
<VirtualHost *:$port>
    DocumentRoot /var/www/server-build
    ServerName $hostname

    # Other directives here
</VirtualHost>
VHOST;

file_put_contents('/etc/httpd/conf.d/vhost-' . strtolower($machine), $vhost);