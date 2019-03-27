# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Changed

- Upgrade to stable MoT client version
- Inject a real MoT client instead of "Dummy"

Waiting for the Ministry of Truth microservice implementation (Go+gin)
and Client fixes (auto-deserialization is not tested yet in 
[symfony-doge/ministry-of-truth-client](https://github.com/symfony-doge/ministry-of-truth-client/tree/0.x)).

## [0.3.0] - 2019-03-27
### Added

- Localization of sanity tag entity fields.
- Localization of sanity tags group entity fields.
- Added `SymfonyDogeMotcBundle` with `symfony_doge.motc.client` service 
as a [bridge](https://github.com/symfony-doge/ministry-of-truth-client/tree/0.x/src/Bridge/Symfony)
between website and external microservice that performs vacancy contextual analysis
(see [symfony-doge/ministry-of-truth-client](https://github.com/symfony-doge/ministry-of-truth-client)).
- Environment for processing vacancy index data: workers, normalizers, entity creators, etc.
- Fixtures for vacancy, company and category entities via [nelmio/alice](https://github.com/nelmio/alice).
- A basic acceptance test for vacancy view history.

### Changed

- Backdating update for sanity entities migration due to missed
`SanityBundle\Entity\Vacancy\Tag::$title` column.
- Code readability refactoring, configuration/services decoupling.

### Fixed

- Fixed potentially bug with array access objects
(`empty` is unsafe for checks if target could be instance of `\ArrayAccess`).
- Hiding salary currency in vacancy details if neither `salaryFrom` nor `salaryTo` numbers present.
- App version bumping for ascii logo now actually works
(managing VERSION file instead of git commands) ([shivas/versioning-bundle](https://github.com/shivas/versioning-bundle)).

Vacancy indexing is still under development.

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

[Unreleased]: https://github.com/symfony-doge/veslo/compare/0.3.0...0.x
[0.3.0]: https://github.com/symfony-doge/veslo/compare/0.2.0..0.3.0
[0.2.0]: https://github.com/symfony-doge/veslo/compare/0.1.0..0.2.0
[0.1.0]: https://github.com/symfony-doge/veslo/releases/tag/0.1.0