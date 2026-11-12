# Development

## Setup

```sh
git clone https://github.com/CeusMedia/Common.git
cd Common
composer dev:install
```

Use `composer dev:install` and `composer dev:update` instead of `composer install` and `composer update`:
some development dependencies require this package themselves, so the scripts set
`COMPOSER_ROOT_VERSION=1.1.x-dev`.

## Tests and static analysis

```sh
composer dev:all                 # syntax, PHPStan, PHPUnit with coverage, API docs (doc-creator)
composer dev:phpunit             # unit tests
composer dev:phpunit-coverage    # unit tests with code coverage
composer dev:phpstan             # static analysis (level 6)
composer dev:test-syntax         # PHP syntax check
composer dev:rector              # show suggestions of Rector rules
```

Configuration files are in `tool/config/`. Tests of network classes start PHP's built-in web server
or a small local FTP server – no internet access and no external services are needed.

## Documentation

This documentation is built with [MkDocs](https://www.mkdocs.org/) and
[Material for MkDocs](https://squidfunk.github.io/mkdocs-material/).

```sh
composer dev:docs                # generate API reference and build site, open doc/Site/index.html
composer dev:docs-serve          # live preview on http://localhost:8000
composer dev:docs-api            # only generate API reference (doc/Manual/api/)
composer dev:docs-install        # install MkDocs into a Python venv (optional, else Docker is used)
```

| Path | Content |
|------|---------|
| `tool/config/mkdocs.yml` | configuration and navigation |
| `doc/Site/` | built HTML site, **generated** |
| `doc/Manual/` | hand-written pages (guides) and assets |
| `doc/Manual/api/` | API reference, **generated** – do not edit |
| `tool/docs/generate-api.php` | generator: reads classes by reflection and their doc blocks |
| `tool/docs/examples/` | examples, included into the API page of a class (same path as class) |
| `tool/docs/docs.sh` | runs MkDocs: local installation, Python venv or Docker |

MkDocs is taken from the first available source:

1. a Python virtual environment in `tool/docs/.venv` (created by `composer dev:docs-install`),
2. `mkdocs` in `PATH` (with `mkdocs-material` installed),
3. the Docker image `squidfunk/mkdocs-material`.

!!! tip "Documenting a class"
    Summary and description of classes and methods come from doc blocks, types from the signatures.
    To add examples to a class page, create a Markdown file in `tool/docs/examples/`,
    e.g. `tool/docs/examples/FS/File.md`.
