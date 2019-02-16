ARG REDIS_VERSION

FROM redis:${REDIS_VERSION}

ARG HOST_UID
ARG TIMEZONE
ARG REDIS_DATA_DIR

WORKDIR ${REDIS_DATA_DIR}

# timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone && \
    date

COPY redis.conf /usr/local/etc/redis/redis.conf

RUN usermod -u ${HOST_UID} redis

USER redis

CMD ["redis-server", "/usr/local/etc/redis/redis.conf"]
