#!/usr/bin/env bash

PROJECT_NAME=teams-connector-bundle

# First remove any already installed packages and if exists compose.lock:
# these can be removed because this is a library and shouldn't exists in the repo anyway!
if [ -d vendor ] || [ -f composer.lock ]
then
  echo 'Please remove "vendor"-directory and "composer.lock"-file before proceeding...'
  exit
fi

# Define supported PHP versions
for phpversion in 7.4 8.0 8.1 8.2
do
  dockerimagename=${PROJECT_NAME}-php-dev:${phpversion}
  echo "Builder docker image for PHP ${phpversion}... (${dockerimagename})"
  docker build --build-arg PHP_VERSION=${phpversion} -f docker/Dockerfile -t ${dockerimagename} . > /dev/null
  echo "Starting container..."
  docker run -d --name ${PROJECT_NAME} --volume $(pwd):/data:rw ${dockerimagename} tail -f /dev/null > /dev/null
  echo "Installing composer packages..."
  docker exec ${PROJECT_NAME} composer install --quiet --no-interaction --no-plugins
  echo "Running PHPUnit..."
  docker exec ${PROJECT_NAME} vendor/bin/phpunit
  echo "Clean up..."
  docker rm -f ${PROJECT_NAME} > /dev/null
  rm -rf vendor composer.lock
done

# Next, test to see if it can be installed in Symfony
PHP_VERSION_FOR_SYMFONY_TESTS=8.2

dockerimagename=${PROJECT_NAME}-php-dev:${PHP_VERSION_FOR_SYMFONY_TESTS}
docker run -d --name ${PROJECT_NAME} --volume $(pwd):/data:rw ${dockerimagename} tail -f /dev/null
for symfonyversion in 4.4 5.4 6.0 6.1 6.2
do
  echo "Create Symfony ${symfonyversion} skeleton..."
  docker exec ${PROJECT_NAME} composer create-project -q -n symfony/skeleton:${symfonyversion}.* /tmp/test-${symfonyversion}
  docker exec -w /tmp/test-${symfonyversion} ${PROJECT_NAME} composer config --no-plugins allow-plugins true
  echo "skrepr_teams_connector:" | docker exec -i ${PROJECT_NAME} tee /tmp/test-${symfonyversion}/config/packages/teams.yaml > /dev/null
  echo "  endpoint: http://localhost/test" | docker exec -i ${PROJECT_NAME} tee -a /tmp/test-${symfonyversion}/config/packages/teams.yaml > /dev/null
  docker exec -w /tmp/test-${symfonyversion} ${PROJECT_NAME} composer config repositories.teams-connector-bundle path /data
  docker exec -w /tmp/test-${symfonyversion} ${PROJECT_NAME} composer require skrepr/teams-connector-bundle:@dev
done
docker rm -f ${PROJECT_NAME} > /dev/null