FROM php:7.3-fpm-alpine

RUN apk add --no-cache \
        bash \
        sed \
        nginx

RUN set -ex; \
    \
    apk add --no-cache --virtual .build-deps \
        libxml2-dev \
    ; \
    \
    docker-php-ext-install mysqli mbstring opcache; \
    \
    runDeps="$( \
        scanelf --needed --nobanner --recursive \
            /usr/local/lib/php/extensions \
            | awk '{ gsub(/,/, "\nso:", $2); print "so:" $2 }' \
            | sort -u \
            | xargs -r apk info --installed \
            | sort -u \
    )"; \
    apk add --virtual .run-deps $runDeps; \
    apk del .build-deps

RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=2'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
        echo 'upload_max_filesize=5M'; \
        echo 'post_max_size=10M'; \
        echo 'file_uploads=On'; \
        echo 'upload_max_filesize=10M'; \
        echo 'max_file_uploads=5'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini


COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/kindlegen /usr/local/bin/kindlegen

COPY docker/docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh && \
    chmod +x /usr/local/bin/kindlegen

WORKDIR /var/www/html
COPY . .

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm"]
