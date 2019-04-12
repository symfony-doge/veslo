<pre>
                                                  ________________________  
                                                 /                        \ 
,--.                                            /                          |
|  |___________________________________________/_____________________      |
|  |_________________________________________________________________)     |
|  |                                           \                           |
`--'                                            \                          |
                                                 \________________________/ 
        Veslo: a vacancy aggregation engine
</pre>

[![Packagist Version](https://img.shields.io/packagist/v/symfony-doge/veslo.svg)](https://packagist.org/packages/symfony-doge/veslo)
[![Quality Score](https://img.shields.io/scrutinizer/g/symfony-doge/veslo.svg)](https://scrutinizer-ci.com/g/symfony-doge/veslo)
[![Code Climate technical debt](https://img.shields.io/codeclimate/tech-debt/symfony-doge/veslo.svg)](https://codeclimate.com/github/symfony-doge/veslo)
[![Packagist](https://img.shields.io/packagist/l/symfony-doge/veslo.svg?color=blue)](https://github.com/symfony-doge/veslo/blob/master/LICENSE)

# Installation

#### Docker

The preferred way to install is through [docker-compose](https://docs.docker.com/compose).
You need to have a [Docker](https://docs.docker.com/install) daemon at least [17.05.0-ce](https://docs.docker.com/engine/release-notes/#17050-ce)
(with build-time `ARG` in `FROM`) to successfully cook all containers.

Run an automated deploy script for local development with Docker:

```
$ bin/deploy_dev.sh
```

![installation asciicast](https://github.com/symfony-doge/veslo/blob/master/.github/images/installation.gif)

#### Manual

You can clone and deploy the application with your own environment
by providing `.env` file using `.env.dev.dist` as a template.
Below are steps to prepare your application before accessing through a web server.

```
$ git clone git@github.com:symfony-doge/veslo.git veslo && cd veslo

// setup your own connection parameters
$ cp .env.dev.dist .env

$ cp app/config/parameters.yml.dist app/config/parameters.yml
$ cp webpack.env.dev.js.dist webpack.env.js
```

Check your environment for Symfony requirements
and install PHP dependencies via [Composer](https://getcomposer.org/download).
You also need the [php-ds](https://github.com/php-ds/ext-ds) extension
for some services working with efficient PHP 7 data structures.

```
$ bin/symfony_requirements
$ composer install
```

Install Javascript dependencies and compile assets via [Yarn](https://yarnpkg.com/lang/en/docs/install)
([Node.js](https://nodejs.org/en/download) 10 is required).

```
$ yarn install
$ yarn run build:dev
```

Apply database migrations.

```
bin/console doctrine:migrations:migrate latest
```

# Workflow

```
FOUND (initial place)
|
| to_parse
 ------------> PARSED
               |
               | to_collect
                ------------> COLLECTED
                              |
                              | to_index
                               ------------> INDEXED
```

# API

```
$ docker-compose run --rm app bin/console veslo:anthill:digging hh php --iterations=1
```

Finds a vacancy from job site by roadmap (search plan) and configuration.
Search result will be offered to parsing queue,
according to current workflow (default `veslo.app.workflow.vacancy_research.to_parse`).

Available roadmaps are defined in `AnthillBundle/Resources/config/roadmaps.yml`
and configurations in `AnthillBundle/Resources/fixtures/roadmap/`.

```
$ docker-compose run --rm app bin/console veslo:anthill:parsing --iterations=1
```

Polls a new raw vacancy data (html/json etc.) for parsing.
Result will be offered to collecting queue
(default `veslo.app.workflow.vacancy_research.to_collect`).

```
$ docker-compose run --rm app bin/console veslo:anthill:collecting --iterations=1
```

Grabs a parsed vacancy data (instance of `AnthillBundle/Dto/Vacancy/RawDto`) 
and decides whether should it be collected for analysis or not.
Result will be persisted in local storage and offered to indexing queue
(default `veslo.app.workflow.vacancy_research.to_index`)

#### Currently supported roadmaps:

* [hh](https://hh.ru) (configurations: `php`)

# Usage example

[www.veslo.it](https://veslo.it)

# Changelog
All notable changes to this project will be documented in [CHANGELOG.md](CHANGELOG.md).