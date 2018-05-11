FROM debian:stretch

RUN apt-get update && apt-get install --no-install-recommends -qy \
    supervisor

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /etc/supervisor/conf.d

COPY supervisord.conf /etc/supervisor/supervisord.conf

RUN usermod -u 1000 www-data

USER www-data

COPY supervisor-entrypoint.sh /usr/local/bin/supervisor-entrypoint
ENTRYPOINT ["supervisor-entrypoint"]

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
