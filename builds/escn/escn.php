<?php
/**
 * An array of commands which are required for to all servers in the 'escn' family (Elasticsearch CLIENT node)
 */

require dirname(dirname(__DIR__)) . '/create/repo/' . basename(__FILE__);  // create the repo

passthru('rm -f /etc/elasticsearch/elasticsearch.yml*', $cc);   // in case we get any .rpmnew issues on upgrade
passthru('yum remove elasticsearch -y', $cc);
passthru('yum install elasticsearch -y', $cc);

$replace = [
    '/^#cluster.name: elasticsearch/im' => 'cluster.name: es-' . $tier,
    '/^#node.master:.+$/im'           => 'node.master: false',
    '/^#node.data:.+$/im'             => 'node.data: false',
    '/^#node.name:.+$/im'             => 'node.name: ' . $hostname,
];

$str = file_get_contents($file = '/etc/elasticsearch/elasticsearch.yml');
foreach ($replace as $from => $to) {
    $str = preg_replace($from, $to, $str, 1);   // only replace first occurrence
}
file_put_contents($file, $str);

return [
    'systemctl daemon-reload',
    'systemctl enable elasticsearch service',
    'service elasticsearch start',
    'sleep 10',
    'curl localhost:9200/_cat/health'
];