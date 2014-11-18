<?php
$port = 80;

$vhost = <<<VHOST
# default virtual host
<VirtualHost *:{$port}>
    DocumentRoot /var/www/
    ServerName {$hostname}

    # Other directives here
</VirtualHost>
VHOST;

file_put_contents('/etc/httpd/conf.d/vhost-00-default.conf', $vhost);

file_put_contents('/var/www/index.html', <<<HTML
<html>
<title>Server {$hostname}</title>
<style type="text/css">
div{
  background: red;
  bottom: 0;
  height: 10em;
  left: 0;
  margin: auto;
  position: absolute;
  top: 0;
  right: 0;
  width: 10em;
}
</style>
<body>
<div>
<h1>{$hostname}</h1>
</div>
</body>
</html>
HTML
);