#!/usr/bin/env sh

set -x

# Run the application container in detached mode, mapping port 80 of the container to port 80 of the host
docker run -d -p 80:80 --name my-apache-php-app -v /c/Users/sia_h/Documents/GitHub/jenkins-php-selenium-test/src:/var/www/html php:7.2-apache

# Wait a bit to ensure the container is up and running
sleep 1

set +x

echo 'Now...'
echo 'Visit http://localhost to see your PHP application in action.'

# Fetch and display the internal IP address of the running container
app_container_ip=$(docker inspect -f '{{range.NetworkSettings.Networks}}{{.IPAddress}}{{end}}' my-apache-php-app)
echo "The application is running and can be accessed within the Docker network at http://$app_container_ip"