#!/bin/bash

set -e
#
#docker-compose -p bp -f docker-compose.yml down
#docker-compose -p bp rm -f
#
#docker-compose -p bp -f docker-compose.yml up -d --force-recreate

docker exec app \
  php vendor/bin/phpunit \
  $*
