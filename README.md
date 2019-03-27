<pre>
                                                  ________________________  
                                                 /                        \ 
,--.                                            /                          |
|  |___________________________________________/_____________________      |
|  |_________________________________________________________________)     |
|  |                                           \                           |
`--'                                            \                          |
                                                 \________________________/ 
                                                                            
      Veslo.IT — независимый рейтинг вакансий
      для IT специалистов в России и странах СНГ
</pre>

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

# Changelog
All notable changes to this project will be documented in [CHANGELOG.md](CHANGELOG.md).