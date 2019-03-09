# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Changed

- Integration with MoT API 0.x.

## [0.2.0] - 2019-03-06
### Security

- Webpack encore environment has upgraded.

### Added

- Workflow schema and base description for API (README.md).
- Database schema with migration for sanity index and related entities.
- Dynamic environment parameters for webpack configuration 
(`publicPath` can now become a CDN entrypoint for assets on prod environment).

### Fixed

- Explicit denormalizer for roadmap data transfer object.
- Result cache for vacancy journal disabled to prevent a broken pagination.
- Strip attributes twig extension now returns a valid injectable html (without any top-level nodes).
- Fixed tests logic when all cases indicates an error if just a single one fails.

## [0.1.0] - 2019-03-02
### Added

- Vacancy aggregation environment based on [Symfony Workflow](https://symfony.com/doc/current/workflow.html) 
and conveyor processing w/ [rabbitmq](https://github.com/rabbitmq)
- Vacancy roadmap (search plan) for [HeadHunter](https://headhunter.ru) job site (API, Version20190213).
- Vacancy roadmap configuration for PHP category (HeadHunter)
- Vacancy list action
- Vacancy show action

Vacancy data is shown "as is", w/o any ratings or transformations.

[Unreleased]: https://github.com/symfony-doge/veslo/compare/0.2.0...HEAD
[0.2.0]: https://github.com/symfony-doge/veslo/compare/0.1.0..0.2.0
[0.1.0]: https://github.com/symfony-doge/veslo/releases/tag/0.1.0