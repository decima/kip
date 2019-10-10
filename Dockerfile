FROM aboutgoods/php-nginx:7.3

USER root
# Installing ldap support
RUN apt-get update && \
    apt-get install -y ldap-utils libldap2-dev && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-install ldap

RUN mkdir /storage && chown -R app:app /storage
USER app

ENV APP_ENV prod


COPY --chown=app:app ./ /app



WORKDIR /app

RUN composer install -o


ENV FILE_STORAGE /storage
