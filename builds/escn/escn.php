<?php
/**
 * An array of commands which are required for to all servers in the 'escn' family (Elasticsearch CLIENT node)
 */

require dirname(dirname(__DIR__)) . '/create/repo/' . basename(__FILE__);  // create the repo

runCommands(
    [
        'service firewalld stop',
        'yum install java -y',
        'service elasticsearch stop',                      // in case it's installed & running already
        'rm -f /etc/elasticsearch/elasticsearch.yml*',     // in case we get any .rpmnew issues on upgrade
        'yum remove elasticsearch -y',
        'yum install elasticsearch -y',
    ]
);

$replace = [
    '/^#(cluster.name:).+$/im'                       => '$1 es-' . $hostinfo['tier'] . '-'. $hostinfo['clusterno'],
    '/^#(node.master:).+$/im'                        => '$1 false',
    '/^#(node.data:).+$/im'                          => '$1 false',
    '/^#(node.name:).+$/im'                          => '$1 ' . $hostinfo['hostname'],
    '/^#(index.number_of_shards:).+$/im'             => '$1 2',
    '/^#(index.number_of_replicas:).+$/im'           => '$1 2',
    '/^#(discovery.zen.minimum_master_nodes:).+$/im' => '$1 2',
];

$str = file_get_contents($file = '/etc/elasticsearch/elasticsearch.yml');
foreach ($replace as $from => $to) {
    $str = preg_replace($from, $to, $str, 1);   // only replace first occurrence
}
file_put_contents($file, $str);

return [
    'systemctl daemon-reload',
    'systemctl enable elasticsearch.service',
    'service elasticsearch start',
    'sleep 10',
    'curl localhost:9200/_cat/health',
];