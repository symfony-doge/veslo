
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
