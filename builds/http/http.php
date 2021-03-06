<?php
/**
 * An array of commands which are required for to all servers in the 'http' family (Apache/PHP)
 */

require dirname(dirname(__DIR__)) . '/create/repo/' . basename(__FILE__);  // create the repo

/*
yum list installed|grep "\-pecl\-"

php-pecl-gnupg.x86_64               1.3.3-2.el6.remi.5.5      @remi-php55
php-pecl-igbinary.x86_64            1.2.1-1.el6.remi.5.5      @remi-php55
php-pecl-imagick.x86_64             3.2.0-0.9.RC1.el6.remi.5.5
php-pecl-imagick-devel.x86_64       3.2.0-0.9.RC1.el6.remi.5.5
php-pecl-jsonc.x86_64               1.3.6-1.el6.remi.5.5.1    @remi-php55
php-pecl-jsonc-devel.x86_64         1.3.6-1.el6.remi.5.5.1    @remi-php55
php-pecl-memcache.x86_64            3.0.8-2.el6.remi.5.5      @remi-php55
php-pecl-memcached.x86_64           2.2.0-2.el6.remi.5.5      @remi-php55
php-pecl-mongo.x86_64               1.5.7-1.el6.remi.5.5      @remi-php55
php-pecl-msgpack.x86_64             0.5.5-4.el6.remi.1        @remi-php55
php-pecl-redis.x86_64               2.2.5-5.el6.remi.5.5      @remi-php55
php-pecl-ssh2.x86_64                0.12-2.el6.remi.5.5       @remi-php55
php-pecl-xdebug.x86_64              2.2.5-1.el6.remi.5.5      @remi-php55
php-pecl-zip.x86_64                 1.12.4-1.el6.remi.5.5     @remi-php55
*/

$php_extensions = [
    'php-mysqlnd',

    'php-pecl-amqp',
    'php-pecl-gnupg',
    'php-pecl-http',
    'php-pecl-igbinary',
    #    'php-pecl-imagick',
    #    'php-pecl-imagick-devel',
    #    'php-pecl-jsonc',
    #    'php-pecl-jsonc-devel',
    'php-pecl-memcached',
    'php-pecl-msgpack',
    'php-pecl-ssh2',
    'php-pecl-xdebug',
    'php-pecl-zip',
];

runCommands(
    [
        'service firewalld stop',  // turn off firewall TODO: make this a permanent change
        'yum install -y httpd',
        'yum install -y nodejs ruby npm',
        # For SELinux
        #        'setenforce 0',  // turn off SELinux. Haven't watched yet https://www.youtube.com/watch?v=MxjenQ31b70
        'yum install -y setroubleshoot setroubleshoot-server', # reboot or restart auditd after you install
        'service auditd restart',
        'setsebool -P httpd_read_user_content 1',
        'setsebool -P httpd_enable_homedirs 1',
        # get Composer
        'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer'
    ]
);

runCommands(
    [
        'yum install -y ' . implode(' ', $php_extensions),
        'rm /etc/httpd/conf.d/vhost* -f',    # get rid of any existing virtual hosts
        'cp ' . dirname(dirname(__DIR__)) . '/create/git/post-merge.sh /root/git-hooks-post-merge',
        'chmod 0755 /root/git-hooks-post-merge',
    ]
);

require dirname(dirname(__DIR__)) . '/create/vhost/' . basename(__FILE__);  // create the vhost

if (file_exists($path = dirname(dirname(__DIR__)) . '/create/vhost/' . $hostinfo['cluster'] . '.php')) {
    require $path;
}

if (file_exists($path = dirname(dirname(__DIR__)) . '/create/vhost/' . $hostinfo['machine'] . '.php')) {
    require $path;
}

return [
    'systemctl daemon-reload',
    'systemctl enable httpd.service',
    'service httpd restart',
    'sleep 10',
#    'curl -I localhost',
    'curl localhost',
];