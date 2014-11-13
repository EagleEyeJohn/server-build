<?php
/**
 * An array of commands which are required for to all servers in the 'esdn' family (Elasticsearch DATA node)
 */

require dirname(dirname(__DIR__)) . '/create/repo/' . basename(__FILE__);  // create the repo

runCommands(
    [
        'yum install java -y',
        'service elasticsearch stop',                      // in case it's installed & running already
        'rm -f /etc/elasticsearch/elasticsearch.yml*',     // in case we get any .rpmnew issues on upgrade
        'yum remove elasticsearch -y',
        'yum install elasticsearch -y',
    ]
);

$replace = [
    '/^#cluster.name: elasticsearch/im' => 'cluster.name: es-' . $tier,
    '/^#node.master:.+$/im'             => 'node.master: true',
    '/^#node.data:.+$/im'               => 'node.data: true',
    '/^#node.name:.+$/im'               => 'node.name: ' . $hostname,
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