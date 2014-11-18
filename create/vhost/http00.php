<?php
// CLUSTER specific vhost

$port = 80;

$vhosts = [
 #   'phoenix-reborn'         => [],
 #   'golden-eagle'           => [],
    'golden-rifleman'        => ['github' => 'https://github.com/Eagle-Eye-Solutions/rifleman.git'],
 #   'hercules'               => [],
 #   'continuous-integration' => [],
 #   'hermes'                 => [],
 #   'consumers'              => [],
 #   'medusa'                 => [],
];

ksort($vhosts);

$key = 1;

foreach ($vhosts as $user => $settings) {
    $document_root = '/var/www/'.$user;

    if (empty($settings['github'])) {
        $settings['github'] = 'https://github.com/Eagle-Eye-Solutions/' . $user . '.git';
    }

    $cmds   = [];
    $cmds[] = 'useradd ' . $user;
#    $cmds[] = 'echo "eagle" | passwd ' . $user . ' --stdin';
    $cmds[] = 'passwd ' . $user . ' -d';        // passwordless
    runCommands($cmds);

    setComposerConfig($user);

    $vhost = <<<VHOST
<VirtualHost *:$port>
    DocumentRoot $document_root
    ServerName $user.$hostname
    ServerAlias www.$user.$hostname

    # {$settings['github']}
    # Other directives here
</VirtualHost>
VHOST;

    $seq = str_pad($key++, 2, '0', STR_PAD_LEFT);
    file_put_contents('/etc/httpd/conf.d/vhost-' . $seq . '-' . $user . '.conf', $vhost);

    $cmds   = [];
    $cmds[] = 'cd /var/www';
    $cmds[] = 'rm -rf ' . $document_root;
    $cmds[] = 'git clone ' . $settings['github'];
    $cmds[] = 'ln -s /root/git-hooks-post-merge ' . $document_root . '/.git/hooks/post-merge';
    $cmds[] = 'chown -r ' . $user . ':' . $user . ' ' . $user;
    runCommands($cmds);
exit;
}
