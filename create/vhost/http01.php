<?php
$port = 80;

/** @var string $machine */

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

    file_put_contents('/etc/httpd/conf.d/vhost-' . ($key++) . '-' . strtolower($machine) . '.conf', $vhost);
}