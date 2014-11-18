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

$em = (3 + strlen($hostname)) . 'em';

file_put_contents(
    '/var/www/index.php', <<<HTML
<?php
\$build_date = date('Y-m-d H:i:s', filemtime(__FILE__));
?>
<html>
<title>Server {$hostname}</title>
<style type="text/css">
div{
  bottom: 0;
  height: 9em;
  left: 0;
  margin: auto;
  position: absolute;
  top: 0;
  right: 0;
  width: {$em};

  border: 1px solid #AAAAAA;
  text-align: center;
  background-color: darkred;
  color: white;
  border-radius: 0.5em;
  box-shadow: 0.25em 0.25em 0.12em #DDDDDD;
}
</style>
<body>
<div>
<h1>{$hostname}</h1>
<p>Build date: <?=\$build_date?></p>
</div>
</body>
</html>
HTML
);
exit;