# Drago Generator

A lightweight CLI tool for generating PHP entity and data class files from your database schema.
Drago Generator is built on Nette, Dibi, and Symfony Console, and allows you to create ready-to-use PHP classes for your tables with configurable constants, references, suffixes, and namespaces.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/generator/blob/main/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fgenerator.svg)](https://badge.fury.io/ph/drago-ex%2Fgenerator)
[![Tests](https://github.com/drago-ex/generator/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/generator/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/generator/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/generator/actions/workflows/coding-style.yml)

## Requirements

- PHP >= 8.3, < 9
- Nette Framework
- Symfony Console
- Dibi
- Composer

## Installation

```
composer require drago-ex/generator --dev
```

## Examples

Run generation commands using the Composer-installed binary:
```bash
# Generate entity classes
php vendor/bin/generator app:entity <table>

# Generate data classes
php vendor/bin/generator app:dataClass <table>
```

```bash
# Generate all entities
php vendor/bin/generator app:entity

# Generate entity for a specific table
php vendor/bin/generator app:entity users

# Generate all data classes
php vendor/bin/generator app:dataClass

# Generate data class for a specific table
php vendor/bin/generator app:dataClass orders
```

## Register Generator Extension in Nette

```neon
extensions:
	generator: Drago\Generator\DI\GeneratorExtension(%consoleMode%)
	console: Contributte\Console\DI\ConsoleExtension(%consoleMode%)

generator:
	path: %appDir%/Entity
	pathDataClass: %appDir%/Data

console:
	name: Symfony Console
	version: 1.0
```

The generator also needs a `Dibi\Connection` service in the container.

## Options

| Option | Default | Description |
|---|---|---|
| `lower` | `false` | Converts column names to lowercase (typical for Oracle). |
| `path` | `` | Directory for entity classes. |
| `namespace` | `App\Entity` | Namespace of entity classes. |
| `suffix` | `Entity` | Suffix of entity class names. |
| `extendsOn` | `true` | Whether generated classes extend a parent class. |
| `extends` | `Drago\Database\Entity` | Parent class of entities. |
| `final` | `false` | Generates final entity classes. |
| `tableName` | `Table` | Name of the constant holding the table name. |
| `primaryKey` | `Id` | Name of the constant holding the auto-increment column. |
| `constant` | `true` | Generates a constant for every column. |
| `constantPrefix` | none | Prefix of column constants. |
| `constantSize` | `false` | Generates `<Column>Size` constants with column sizes. |
| `columnInfo` | `false` | Adds comments with the primary key, default value and size. |
| `references` | `false` | Adds properties for foreign key references. |
| `pathDataClass` | `` | Directory for data classes. |
| `namespaceDataClass` | `App\Data` | Namespace of data classes. |
| `suffixDataClass` | `Data` | Suffix of data class names. |
| `extendsDataClass` | `Drago\Utils\ExtraArrayHash` | Parent class of data classes. |
| `finalDataClass` | `false` | Generates final data classes. |
| `constantDataClass` | `true` | Generates a constant for every column. |
| `constantDataPrefix` | none | Prefix of column constants in data classes. |
| `constantSizeDataClass` | `true` | Generates `<Column>Size` constants. |
| `referencesDataClass` | `false` | Adds properties for foreign key references. |

The `drago-ex/database` package uses `PrimaryKey` as the name of the primary key constant in its examples.
Set `primaryKey: PrimaryKey` to generate the same name.

Column types are mapped to `int`, `float`, `bool`, `string` and `\DateTimeImmutable`. Binary columns
(BLOB, BINARY) are generated as `string`, which is what Dibi returns for them. Column names containing
parentheses (for example from a view) are rejected; use an alias.

## Features

- Generate entity and data classes from database tables
- Configurable constants and column size constants
- Support for foreign key references
- Set custom suffixes, namespaces, and final classes
- Symfony Console integration for a clean CLI

## CLI requirements

`vendor/bin/generator` runs from the project root. It loads `app/Bootstrap.php` and calls
`App\Bootstrap::bootConsoleApplication()`, which must return the DI container with the generator and
console extensions registered. For projects with a different structure, write your own small script
that creates the container and runs `Contributte\Console\Application`.

## Running the tests

Database tests run against MySQL. Import `tests/db.sql` and set the connection:

```bash
GENERATOR_DB_HOST=127.0.0.1 GENERATOR_DB_PORT=3306 GENERATOR_DB_USER=root GENERATOR_DB_PASSWORD=root GENERATOR_DB_NAME=test composer tester
```

Without `GENERATOR_DB_HOST` the database tests are skipped.
