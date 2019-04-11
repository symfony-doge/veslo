<pre>
                                                  ________________________  
                                                 /                        \ 
,--.                                            /                          |
|  |___________________________________________/_____________________      |
|  |_________________________________________________________________)     |
|  |                                           \                           |
`--'                                            \                          |
                                                 \________________________/ 

</pre>

[![Packagist Version](https://img.shields.io/packagist/v/symfony-doge/veslo.svg)](https://packagist.org/packages/symfony-doge/veslo)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/symfony-doge/veslo.svg)](https://www.php.net/releases/7_2_0.php)
[![Quality Score](https://img.shields.io/scrutinizer/g/symfony-doge/veslo.svg)](https://scrutinizer-ci.com/g/symfony-doge/veslo)
[![Packagist](https://img.shields.io/packagist/l/symfony-doge/veslo.svg?color=9cf)](https://github.com/symfony-doge/veslo/blob/master/LICENSE)

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

# Installation 

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

# Websites

[www.veslo.it](https://veslo.it)

# Changelog
All notable changes to this project will be documented in [CHANGELOG.md](CHANGELOG.md).