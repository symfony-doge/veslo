
# Copyright (C) 2019 Pavel Petrov <itnelo@gmail.com>
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>.

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
