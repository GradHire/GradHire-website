#!/bin/bash

# Variables
DOCKER_IMAGE_NAME="gradhire"
DOCKER_CONTAINER_NAME="gradhire"
INTERN_PORT=8080
EXTERN_PORT=80

# Arrêter et supprimer le conteneur existant s'il y en a un
if docker ps -a | grep -q $DOCKER_CONTAINER_NAME; then
    echo "Arrêt du conteneur existant..."
    docker stop $DOCKER_CONTAINER_NAME
    echo "Suppression du conteneur existant..."
    docker rm $DOCKER_CONTAINER_NAME
fi

# Build de l'image Docker
echo "Construction de l'image Docker..."
docker build -t $DOCKER_IMAGE_NAME -f Dockerfile .

# Lancement du nouveau conteneur
echo "Lancement du nouveau conteneur..."
docker run -d -p "$EXTERN_PORT":"$INTERN_PORT" --name $DOCKER_CONTAINER_NAME $DOCKER_IMAGE_NAME
