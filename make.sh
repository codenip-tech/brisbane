#!/usr/bin/env bash
set -e
export U_ID=$(id -u)
DOCKER_BE=brisbane
COMMAND=$1
docker network create brisbane-network > /dev/null || true

if [[ "${COMMAND}" == "ssh-be" ]]; then
    docker exec -it --user "${U_ID}" "${DOCKER_BE}" bash
elif [[ "${COMMAND}" == "start" ]]; then
  docker network create brisbane-network 2> /dev/null || true
  docker-compose up -d
elif [[ "${COMMAND}" == "stop" ]]; then
  docker-compose stop
elif [[ "${COMMAND}" == "build" ]]; then
  docker network create brisbane-network 2> /dev/null || true
  docker-compose build
else
  echo "Command ${COMMAND} not implemented"
fi