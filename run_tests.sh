#!/bin/bash

set -e

IMAGE_NAME="php:7.4-fpm"

docker run -it --rm \
  --name bp_task \
  -v "$PWD":/var/www \
  -w /var/www \
  ${IMAGE_NAME} \
  php laravel/vendor/bin/phpunit \
  $*