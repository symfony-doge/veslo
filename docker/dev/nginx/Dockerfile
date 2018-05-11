FROM debian:stretch

ARG HOST_UID

WORKDIR /etc/nginx

USER root

RUN apt-get update && apt-get install --no-install-recommends -qy \
        nginx

COPY nginx.conf /etc/nginx/
COPY veslo.it.conf /etc/nginx/sites-available/

RUN ln -s /etc/nginx/sites-available/veslo.it.conf /etc/nginx/sites-enabled/veslo.it
RUN rm /etc/nginx/sites-enabled/default

RUN usermod -u ${HOST_UID} www-data

CMD ["nginx"]

EXPOSE 8080
