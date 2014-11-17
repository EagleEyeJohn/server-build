<?php
/**
 * An array of commands which are required for to all servers in the 'msql' family
 */

require dirname(dirname(__DIR__)).'/create/vhost/'.basename(__FILE__);

runCommands(
    [
        'yum mysql-server -y'
    ]
);

//TODO: replace MySQL config file

return [
];