#!/bin/bash

docker build -t gradhire -f Dockerfile .
docker run -d -p 80:8080 gradhire