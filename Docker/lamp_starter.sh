#!/bin/bash

service apache2 start
service mysql start

# Keep the Docker container alive
sleep infinity
