#!/usr/bin/env bash
set -e
export U_ID=$(id -u)
COMMAND=$1
DOCKER_BE=$2

case ${COMMAND} in
  ssh-be)
    docker exec -it --user "${U_ID}" "${DOCKER_BE}" bash
    ;;
  start)
    docker network create brisbane-network 2> /dev/null || true
    U_ID=${UID} docker-compose up -d
    ;;
  down-clean)
    U_ID=${UID} docker-compose down -v
    ;;
  stop)
    U_ID=${UID} docker-compose stop
    ;;
  build)
	  cp -n docker-compose.yml.dist docker-compose.yml || true
    docker network create brisbane-network 2> /dev/null || true
    U_ID=${UID} docker-compose build
    ;;
  code-style)
    docker exec --user ${UID} ${DOCKER_BE} php-cs-fixer fix src --rules=@Symfony
    ;;
  code-style-check)
    docker exec --user ${UID} ${DOCKER_BE} php-cs-fixer fix src --rules=@Symfony --dry-run
    ;;
  run)
    U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} symfony serve -d
    ;;
  logs)
    U_ID=${UID} docker exec -it --user ""${UID} ${DOCKER_BE} symfony server:log
    ;;
  composer-install)
    U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} composer install --no-interaction
    ;;
  tests)
	  U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} vendor/bin/simple-phpunit -c phpunit.xml.dist 2> /dev/null || true
    ;;
  generate-private-key)
    openssl genrsa -out private.key 2048
    chmod 644 private.key
    ;;
  generate-public-key)
    openssl rsa -in private.key -pubout -out public.key
    ;;
  *)
    echo "Command ${COMMAND} not implemented"
    ;;
esac
