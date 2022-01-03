FROM php:7.1-cli

RUN apt-get update
#RUN apt-get install -y git unzip

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions @composer-2
RUN install-php-extensions zlib iconv xdebug
