#!/bin/bash

set -e

docker-compose -p bp -f docker-compose.yml up -d

docker exec app \
  php vendor/bin/phpunit \
  $*
