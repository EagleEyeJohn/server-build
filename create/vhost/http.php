<?php
$port = 80;

/** @var string $machine */

$vhost = <<<VHOST
# default virtual host
<VirtualHost *:$port>
    DocumentRoot /var/www/
    ServerName $hostname

    # Other directives here
</VirtualHost>
VHOST;

file_put_contents('/etc/httpd/conf.d/vhost-00-' . strtolower($machine) . '.conf', $vhost);

file_put_contents('/var/www/index.html', <<<'HTML'
<html>
<title>Server $hostname</title>
<body>
<h1>$hostname</h1>
</body>
</html>
HTML
);