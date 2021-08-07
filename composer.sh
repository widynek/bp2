#!/usr/bin/env bash
set -e

COMPOSER_COMMAND=$*

if [ -z "${COMPOSER_COMMAND}" ]; then
  echo "##############################################################################################"
  echo "Usage:"
  echo "composer.sh install | composer.sh --version | composer.sh <any_other_composer_command>"
  echo "##############################################################################################"
  exit
fi

docker pull composer

docker run --rm --interactive --tty \
    --volume $PWD/laravel:/var/www/ \
    --name composer \
    --workdir "/var/www/" \
    composer ${COMPOSER_COMMAND}

