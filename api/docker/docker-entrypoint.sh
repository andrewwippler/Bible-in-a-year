#!/bin/bash
set -euo pipefail
chmod 777 /var/www/html -R
# start nginx
/usr/sbin/nginx -g 'daemon off;pid /run/nginx.pid;' &

# php-fpm
exec "$@"
