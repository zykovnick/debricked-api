FROM phpdockerio/php80-fpm:latest
WORKDIR "/app"

RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        php8.0-sqlite3 php8.0-yaml php8.0-xdebug ; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
