#!/usr/bin/env bash
set -e
U_IDX=$(id -u)
U_ID=$(($U_IDX%1000)) #To prevent large numbers and avoid conflicts with Docker

export U_ID

DOCKER_BE=brisbane-app
COMMAND=$1
docker network create brisbane-network > /dev/null || true

case ${COMMAND} in
  ssh-be)
    docker exec -it --user "${U_ID}" "${DOCKER_BE}" bash
    ;;
  start)
    docker network create brisbane-network 2> /dev/null || true
    docker-compose up -d
    ;;
  stop)
    docker-compose stop
    ;;
  build)
    docker network create brisbane-network 2> /dev/null || true
    docker-compose build
    ;;
  code-style)
    docker exec --user ${UID} ${DOCKER_BE} php-cs-fixer fix src --rules=@Symfony
    ;;
  code-style-check)
    docker exec --user ${UID} ${DOCKER_BE} php-cs-fixer fix src --rules=@Symfony --dry-run
    ;;
  *)
    echo "Command ${COMMAND} not implemented"
    ;;
esac