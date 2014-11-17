<?php
// CLUSTER specific vhost

$port = 80;

$vhosts = [
    'phoenix-reborn' => [],
    'greggs'         => [],
    'greggs-portal'  => [],
];

$key = 1;

foreach ($vhosts as $site => $settings) {
    $vhost = <<<VHOST
<VirtualHost *:$port>
    DocumentRoot /var/www/$site
    ServerName $site.$hostname
    ServerAlias www.$site.$hostname

    # Other directives here
</VirtualHost>
VHOST;

    $seq = str_pad($key++, 2, '0', STR_PAD_LEFT);
    file_put_contents('/etc/httpd/conf.d/vhost-' . $seq . '-' . $site . '.conf', $vhost);
}