#!/usr/bin/env bash
set -e
export U_ID=$(id -u)
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
  *)
    echo "Command ${COMMAND} not implemented"
    ;;
esac