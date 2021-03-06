<?php
// undo the substitution that will take place below (so $basearch remains exactly that),
// so it's easy to copy content from an existing '/etc/yum.repos.d/remi.repo' and pasted in here to update
$basearch = '$basearch';


$repo = <<<REPO
[remi]
name=Les RPM de remi pour Enterprise Linux 7 - $basearch
#baseurl=http://rpms.famillecollet.com/enterprise/7/remi/$basearch/
mirrorlist=http://rpms.famillecollet.com/enterprise/7/remi/mirror
enabled=1
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-remi

[remi-php55]
name=Les RPM de remi de PHP 5.5 pour Enterprise Linux 7 - $basearch
#baseurl=http://rpms.famillecollet.com/enterprise/7/php55/$basearch/
mirrorlist=http://rpms.famillecollet.com/enterprise/7/php55/mirror
# WARNING: If you enable this repository, you must also enable "remi"
enabled=0
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-remi

[remi-php56]
name=Les RPM de remi de PHP 5.6 pour Enterprise Linux 7 - $basearch
#baseurl=http://rpms.famillecollet.com/enterprise/7/php56/$basearch/
mirrorlist=http://rpms.famillecollet.com/enterprise/7/php56/mirror
# WARNING: If you enable this repository, you must also enable "remi"
enabled=1
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-remi

[remi-test]
name=Les RPM de remi en test pour Enterprise Linux 7 - $basearch
#baseurl=http://rpms.famillecollet.com/enterprise/7/test/$basearch/
mirrorlist=http://rpms.famillecollet.com/enterprise/7/test/mirror
# WARNING: If you enable this repository, you must also enable "remi"
enabled=0
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-remi

[remi-debuginfo]
name=Les RPM de remi pour Enterprise Linux 7 - $basearch - debuginfo
baseurl=http://rpms.famillecollet.com/enterprise/7/debug-remi/$basearch/
enabled=0
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-remi

[remi-php55-debuginfo]
name=Les RPM de remi de PHP 5.5 pour Enterprise Linux 7 - $basearch - debuginfo
baseurl=http://rpms.famillecollet.com/enterprise/7/debug-php55/$basearch/
enabled=0
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-remi

[remi-php56-debuginfo]
name=Les RPM de remi de PHP 5.6 pour Enterprise Linux 7 - $basearch - debuginfo
baseurl=http://rpms.famillecollet.com/enterprise/7/debug-php56/$basearch/
enabled=0
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-remi

[remi-test-debuginfo]
name=Les RPM de remi en test pour Enterprise Linux 7 - $basearch - debuginfo
baseurl=http://rpms.famillecollet.com/enterprise/7/debug-test/$basearch/
enabled=0
gpgcheck=1
gpgkey=file:///etc/pki/rpm-gpg/RPM-GPG-KEY-remi

REPO;

file_put_contents('/etc/yum.repos.d/remi.repo', $repo);

copy('http://rpms.famillecollet.com/RPM-GPG-KEY-remi', '/etc/pki/rpm-gpg/RPM-GPG-KEY-remi');
