#!/bin/bash

sudo docker stop $(sudo docker ps -q)
docker build -t gradhire -f Dockerfile .
docker run -d -p 80:8080 gradhire