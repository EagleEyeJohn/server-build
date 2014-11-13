<?php

/**
 * AAAANNNN.tier.localdomain
 * AAAANNNN   = machine
 * AAAANN     = cluster
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
