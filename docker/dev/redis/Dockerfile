
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
