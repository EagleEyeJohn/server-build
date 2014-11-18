<?php

/**
 * AAAANNNN.tier.localdomain
 * AAAANNNN   = machine
 * AAAANN     = cluster
 *     NN     = clusterno
 * AAAA       = family
 *       NN   = instance (unique within tier+family, not-enforced)
 *
 * @param $hostname
 *
 * @return array|bool
 */
function parseHostname($hostname)
{
    if (!preg_match('/^((([a-z]{4})(\d\d))(\d\d))\.([^\.]+)/i', $hostname, $matches)) {
        return false;
    }

    $machine   = strtolower($matches[1]);
    $cluster   = strtolower($matches[2]);
    $family    = strtolower($matches[3]);
    $clusterno = strtolower($matches[4]);   // last 2 digits of cluster
    $instance  = strtolower($matches[5]);
    $tier      = strtolower($matches[6]);

    return compact('hostname', 'matches', 'machine', 'cluster', 'clusterno', 'family', 'instance', 'tier');
}

function runCommands(array $cmds)
{
    foreach ($cmds as $cmd) {
        echo PHP_EOL . COL_YELLOW . $cmd . PHP_EOL . COL_RESET . PHP_EOL;
        passthru($cmd, $cc);
    }
}

#print_r(parseHostname('escn0000.dev.localdomain'));


function setComposerConfig($user)
{
    $dir = '/home/' . $user . '/composer';

    if (!is_dir($dir)) {
        mkdir($dir, 0755);
        chown($dir, $user);
        chgrp($dir, $user);
    }

    file_put_contents(
        $path = $dir . '/config.json', <<<COMPOSER
{
    "config": {
        "github-oauth": {
            "github.com": "db9cd5a15fc8340c707668b21381c4e4cb609d9c"
        }
    }
}
COMPOSER
    );

    chown($path, $user);
    chgrp($path, $user);
}