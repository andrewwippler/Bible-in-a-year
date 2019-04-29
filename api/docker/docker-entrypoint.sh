#!/bin/bash
set -euo pipefail

# start nginx
/usr/sbin/nginx -g 'daemon off;pid /run/nginx.pid;' &

# php-fpm
exec "$@"
