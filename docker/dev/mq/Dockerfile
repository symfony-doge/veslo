ARG RABBITMQ_VERSION

FROM rabbitmq:${RABBITMQ_VERSION}

ARG HOST_UID
ARG TIMEZONE
ARG RABBITMQ_DATA_DIR
ARG RABBITMQ_CONFIG_FILE

WORKDIR ${RABBITMQ_DATA_DIR}

# system
RUN apt-get update && apt-get install --no-install-recommends -qy \
        tzdata

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone && \
    date

COPY rabbitmq.conf ${RABBITMQ_CONFIG_FILE}.additional.conf
RUN cat ${RABBITMQ_CONFIG_FILE}.additional.conf >> ${RABBITMQ_CONFIG_FILE}.conf

RUN usermod -u ${HOST_UID} rabbitmq

USER rabbitmq

CMD ["rabbitmq-server"]
