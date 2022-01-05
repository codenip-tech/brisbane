#!/usr/bin/env bash
export U_ID=$(id -u)
DOCKER_BE=codenip-php81-symfony54
COMMAND=$1

if [[ "${COMMAND}" == "ssh-be" ]]; then
    docker exec -it --user "${U_ID}" "${DOCKER_BE}" bash
fi