# Agent Guide

Use this file as package-specific guidance when working in this repository. Keep changes aligned with the existing public API, project conventions, and supported PHP range.

## Package Overview
- **Package**: `drago-ex/generator`
- **Type**: library
- **Purpose**: A lightweight CLI tool for generating PHP entity and data class files from your database schema.
- **PHP**: >=8.3 <9
- **Runtime dependencies**: contributte/console (^0.10), doctrine/inflector (^2.0), drago-ex/utils (^2.0), drago-ex/database (^3.0 || ^4.0), nette/php-generator (^4.1)
- **License**: MIT

## Repository Layout
- `bin/`: command-line entry points.
- `src/`: package source code.
- `tests/`: automated tests.
- `.github/`: CI workflows; keep their PHP versions and commands consistent with Composer and the package scripts.
- `composer.json`: package metadata, dependency constraints, autoloading, and available Composer scripts.

## Key Parts
- `Generator\EntityGenerator` and `DataClassGenerator` write classes from table reflection; `Base::detectType()` maps native types (binary columns become `string`; `tinyint(1)` stays `int`, because Dibi returns it as an integer). Data classes serve form values, so the auto-increment column is `?int $id = null` there (an add form has no id); entities keep it non-null.
- `Options` is the only place of the defaults: the config schema of `DI\GeneratorExtension` is `Expect::from(new Options)` and the processed `Options` object goes to both generators (tested in `tests/Generator/DI`).
- The commands are registered only in console mode and found by contributte/console by type, named by `#[AsCommand]`; do not tag them `console.command` (a tag without a name does nothing, and tagging every `Command` in the container touched other packages too).
- Foreign keys are read only with `references` / `referencesDataClass`; drivers without foreign key reflection (`Dibi\NotSupportedException`) generate no references.
- Database tests need `GENERATOR_DB_HOST` (optionally `_PORT`, `_USER`, `_PASSWORD`, `_NAME`) and `tests/db.sql`; generated output is compared with `tests/Generator/expected/*.expected`, which must be updated when the output changes on purpose.
- `bin/generator` requires `App\Bootstrap::bootConsoleApplication()` in the consuming project.

## Dependencies

Composer constraints describe the package contract. Before changing integration behavior, inspect the source and the actual call sites in this package; do not infer runtime usage solely from dependency declarations.
Development dependencies include nette/bootstrap (^3.2), nette/tester (^2.5), tracy/tracy (^2.10), phpstan/phpstan-nette (^2).

## Essential Commands

```bash
composer tester                 # tester tests -s
composer phpstan                # phpstan analyse
```

## Conventions & Rules

- Preserve backward compatibility unless the task explicitly permits a breaking change. Check callers and public signatures before changing behavior.
- Follow the existing PHP style: strict types, explicit parameter and return types, tabs for indentation, and single-quoted strings where practical. Match nearby code when a convention is unclear.
- Keep package-specific configuration, resources, and migrations in their established directories. Do not edit generated or consuming-project files without confirming they are part of this repository’s intended output.
- Add or update focused tests for behavioral changes when this package has a runnable test setup. Do not invent test commands; use only the scripts and configuration present here.
- Update README examples and Composer metadata when a public API or supported requirement changes.
