<?php
/** @var string $machine */

$repo = <<<REPO
[elasticsearch-1.4]
name=Elasticsearch repository for 1.4.x packages
baseurl=http://packages.elasticsearch.org/elasticsearch/1.4/centos
gpgcheck=1
gpgkey=http://packages.elasticsearch.org/GPG-KEY-elasticsearch
enabled=1
REPO;

file_put_contents('/etc/yum.repos.d/elasticsearch.repo', $repo);