<?php
/**
 * An array of commands which are required for to all servers in the 'stor' family
 */

require dirname(dirname(__DIR__)) . '/create/repo/' . basename(__FILE__);  // create the repo

passthru('rm -f /etc/elasticsearch/elasticsearch.yml*', $cc);   // in case we get any .rpmnew issues on upgrade

passthru('yum install elasticsearch -y', $cc);

$replace = [
];

$str = file_get_contents($file = '/etc/elasticsearch/elasticsearch.yml');
$str = str_replace(array_keys($replace), $replace, $str);
file_put_contents($file, $str);

return [
];