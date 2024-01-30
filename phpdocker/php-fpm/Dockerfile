FROM phpdockerio/php:8.3-fpm
WORKDIR "/application"

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        git \ 
        php8.3-amqp \ 
        php8.3-bcmath \ 
        php8.3-bz2 \ 
        php8.3-cgi \ 
        php8.3-dba \ 
        php8.3-ds \ 
        php8.3-gd \ 
        php8.3-http \ 
        php8.3-imap \ 
        php8.3-intl \ 
        # php8.3-mcrypt \ 
        php8.3-memcache \ 
        php8.3-memcached \ 
        php8.3-mysql \ 
        php8.3-oauth \ 
        php8.3-odbc \ 
        php8.3-psr \ 
        php8.3-redis \ 
        php8.3-snmp \ 
        php8.3-soap \ 
        php8.3-sqlite3 \ 
        php8.3-ssh2 \ 
        php8.3-xdebug \ 
        php8.3-yaml \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
