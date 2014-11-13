<?php
/**
 * An array of commands which are required for to all servers in the 'http' family (Apache/PHP)
 */

require dirname(dirname(__DIR__)) . '/create/repo/' . basename(__FILE__);  // create the repo

runCommands(
    [
        'service firewalld stop',
        'yum install httpd -y',
        'yum install php -y',
    ]
);

return [
#    'systemctl daemon-reload',
#    'systemctl enable httpd.service',
#    'service httpd start',
#    'sleep 10',
];