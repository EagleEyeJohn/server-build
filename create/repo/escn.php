<?php
/** @var string $machine */

$repo = <<<REPO
[elasticsearch-1.3]
name=Elasticsearch repository for 1.3.x packages
baseurl=http://packages.elasticsearch.org/elasticsearch/1.3/centos
gpgcheck=1
gpgkey=http://packages.elasticsearch.org/GPG-KEY-elasticsearch
enabled=1
REPO;

file_put_contents('/etc/yum.repos.d/elasticsearch.repo', $repo);