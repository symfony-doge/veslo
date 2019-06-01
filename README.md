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

## Installation

### Docker

The preferred way to install is through [docker-compose](https://docs.docker.com/compose).
You need to have a [Docker](https://docs.docker.com/install) daemon at least [17.05.0-ce](https://docs.docker.com/engine/release-notes/#17050-ce)
(with build-time `ARG` in `FROM`) to successfully cook all containers.

Run an automated deploy script for local development with Docker.

```
$ bin/deploy_dev.sh
```

![installation asciicast](https://github.com/symfony-doge/veslo/blob/master/.github/images/installation.gif)

Roadmap configurations were not automatically loaded by the script,
you need to insert them manually with a separate command.

```
$ docker-compose run --rm app bin/console doctrine:fixtures:load --group roadmap.configuration.parameters --append
```

### Manual

You can clone and deploy the application with your own environment
by providing `.env` file using `.env.dev.dist` as a template.
Below are steps to prepare your application before accessing through a web server.

```
$ git clone git@github.com:symfony-doge/veslo.git veslo && cd "$_"

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
([Node.js](https://nodejs.org/en/download) 10.x is required).

```
$ yarn install
$ yarn run build:dev
```

Apply database migrations.

```
$ bin/console doctrine:migrations:migrate latest
```

Load roadmap configurations.

```
$ bin/console doctrine:fixtures:load --group roadmap.configuration.parameters --append
```

## Testing

Loading fixtures.

```
$ docker-compose run --rm app bin/console doctrine:fixtures:load --group test
```

Applying [Codeception](https://codeception.com/docs/reference/Commands) parameters.

```
$ cp parameters.codeception.dev.yml.dist parameters.codeception.yml
```

Executing tests.

```
$ docker-compose run --rm --no-deps app bin/codecept run --steps
```

### Workflow

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

There is a set of log files suited for debugging an each workflow transition.
`var/logs` contains:

| Log file | Description |
| :--- | :--- |
| `dev.anthill.digging-YYYY-mm-dd.log` | Search process (to_parse) |
| `dev.anthill.parsing-YYYY-mm-dd.log` | Parsing process (to_collect) |
| `dev.anthill.collecting-YYYY-mm-dd.log` | Saving in local storage (to_index) |  
| `dev.sanity.indexing-YYYY-mm-dd.log` | Analysis |
| `dev.app.workflow-YYYY-mm-dd.log` | Queue get/push events<br /> (data distribution between workers) |  
| `dev.app.http-YYYY-mm-dd.log` | Dumping all HTTP requests/responses<br /> (works if `app.http_client.logging` is `true`) |
| `dev-YYYY-mm-dd.log` | A common Symfony log file |

## API

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
Result will be persisted in the local storage and offered to the indexing queue
(default `veslo.app.workflow.vacancy_research.to_index`)

```
$ docker-compose run --rm app bin/console veslo:sanity:indexing --iterations=1
```

Sends an accepted vacancy to the microservice for analysis (see `ANALYSER_HOST`, `ANALYSER_PORT`)
and persists received metadata in the local storage. Remote service implements
API defined by [symfony-doge/ministry-of-truth-client](https://github.com/symfony-doge/ministry-of-truth-client) bridge.
Example (Go + Gin): [symfony-doge/ministry-of-truth-cis](https://github.com/symfony-doge/ministry-of-truth-cis).

### Currently supported roadmaps:

* [hh](https://hh.ru) (configurations: `php`, `javascript`, `golang`, `python`, `java`, `c++`)

## Usage example

[www.veslo.it](https://veslo.it)

## Changelog
All notable changes to this project will be documented in [CHANGELOG.md](CHANGELOG.md).