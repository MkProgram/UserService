ARG NGINX_VERSION=1.16

FROM nginx:${NGINX_VERSION}-alpine

WORKDIR /var/www/html

COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/

COPY ./app/public public/