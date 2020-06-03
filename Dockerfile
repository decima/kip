FROM composer AS phpdeps
WORKDIR /app
ADD composer.json /app/composer.json
ADD composer.lock /app/composer.lock
RUN composer install --no-dev

FROM node:lts as nodebuild
RUN mkdir -p /app
WORKDIR /app
ADD package.json /app/package.json
ADD webpack.config.js /app/
ADD package-lock.json /app/package-lock.json
RUN npm install

ADD ./.babelrc /app/.babelrc
ADD ./assets /app/assets
ADD ./public /app/public
RUN npm run build


FROM decima/php-nginx:7.4 AS runner

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
COPY --from=nodebuild --chown=app:app /app/public /app/public
COPY --from=phpdeps --chown=app:app /app/vendor /app/vendor


WORKDIR /app
RUN echo "BUILD_VERSION=$(git rev-parse --short HEAD)" >> .env.prod.local
RUN composer install -o

ENV FILE_STORAGE /storage
