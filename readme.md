# Drago Generator

Generates PHP entity classes (for drago-ex/database) and data classes (form values) from the tables of a
database, from the command line.

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

## Register Generator Extension in Nette

```neon
extensions:
	generator: Drago\Generator\DI\GeneratorExtension(%consoleMode%)
	console: Contributte\Console\DI\ConsoleExtension(%consoleMode%)

generator:
	path: %appDir%/Entity
	pathDataClass: %appDir%/Values

console:
	name: Symfony Console
	version: 1.0
```

The generator also needs a `Dibi\Connection` service in the container.

## Usage

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

## Options

| Option | Default | Description |
|---|---|---|
| `lower` | `false` | Converts column names to lowercase (typical for Oracle). |
| `camelCase` | `true` | Names properties in camelCase (column `created_at` gives `$createdAt`; a column that would not come back from it, such as `address_2`, keeps its name); `false` names them as the columns. |
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
| `references` | `false` | Adds properties for foreign key references, named after the table (after the key column without `_id` when the table is referenced more than once). |
| `pathDataClass` | `` | Directory for data classes. |
| `namespaceDataClass` | `App\Values` | Namespace of data classes. |
| `suffixDataClass` | `Values` | Suffix of data class names. |
| `extendsDataClass` | `Drago\Utils\ExtraArrayHash` | Parent class of data classes. |
| `finalDataClass` | `false` | Generates final data classes. |
| `constantDataClass` | `true` | Generates a constant for every column. |
| `constantDataPrefix` | none | Prefix of column constants in data classes. |
| `constantSizeDataClass` | `true` | Generates `<Column>Size` constants. |
| `referencesDataClass` | `false` | Adds properties for foreign key references, named like in entities; nullable for a nullable key. |

A data class holds the values of a form (`$form->getValues(OrdersValues::class)`), so its default name ends with
`Values`, like the form value classes of the drago-ex packages (e.g. `ArticleValues`). Its constants name the form
controls, so with `camelCase` they hold the property names (`CreatedAt = 'createdAt'`), while the constants of an
entity hold the column names for queries (`CreatedAt = 'created_at'`); drago-ex/database saves the values into the
columns.

The `drago-ex/database` package uses `PrimaryKey` as the name of the primary key constant in its examples.
Set `primaryKey: PrimaryKey` to generate the same name.

Column types are mapped to `int`, `float`, `bool`, `string` and `\DateTimeImmutable`. Binary columns
(BLOB, BINARY) are generated as `string`, which is what Dibi returns for them, and `TINYINT(1)` (MySQL
BOOLEAN) as `bool`, which drago-ex/database converts. Column names containing
parentheses (for example from a view) are rejected; use an alias.

## Projects with Another Structure

`vendor/bin/generator` runs from the project root. It loads `app/Bootstrap.php` and calls
`App\Bootstrap::bootConsoleApplication()`, which must return the DI container with the generator and
console extensions registered. For projects with a different structure, write your own small script
that creates the container and runs `Contributte\Console\Application`.

