ARG NGINX_VERSION

FROM nginx:${NGINX_VERSION}

ARG HOST_UID
ARG TIMEZONE
ARG NGINX_PORT_EXPOSE

WORKDIR /etc/nginx

USER root

# timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone && \
    date

COPY nginx.conf /etc/nginx/
COPY veslo.it.conf /etc/nginx/sites-available/

RUN ln -s /etc/nginx/sites-available/veslo.it.conf /etc/nginx/conf.d/veslo.it.conf && \
    rm /etc/nginx/conf.d/default.conf

RUN usermod -u ${HOST_UID} www-data

EXPOSE ${NGINX_PORT_EXPOSE}

CMD ["nginx-debug"]
