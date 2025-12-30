## Drago Generator
A lightweight CLI tool for generating PHP entity and data class files from your database schema.

Drago Generator is built on Nette, Dibi, and Symfony Console, and allows you to create ready-to-use PHP classes for your tables with configurable constants, references, suffixes, and namespaces.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/generator/master/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fgenerator.svg)](https://badge.fury.io/ph/drago-ex%2Fgenerator)
[![Tests](https://github.com/drago-ex/generator/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/generator/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/generator/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/generator/actions/workflows/coding-style.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/generator/badge)](https://www.codefactor.io/repository/github/drago-ex/generator)
[![Coverage Status](https://coveralls.io/repos/github/drago-ex/generator/badge.svg?branch=master)](https://coveralls.io/github/drago-ex/generator?branch=master)

## Requirements
PHP >= 8.3
Nette Framework
Symfony Console
Dibi
Composer

## Installation
```
composer require drago-ex/generator --dev
```

## Register Generator Extension in Nette
```neon
extensions:
    generator: Drago\Generator\DI\GeneratorExtension(%consoleMode%)
    console: Contributte\Console\DI\ConsoleExtension(%consoleMode%)

# generator
generator:
	# base (typical of Oracle)
	lower: false

	# generator entity
	path: %appDir%/Entity
	tableName: 'Table'
	primaryKey: 'PrimaryKey'
	columnInfo: false
	constant: true
	constantSize: false
	constantPrefix: 'Column'
	references: false
	suffix: Entity
	extendsOn: true
	extends: Drago\Database\Entity
	final: false
	namespace: App\Entity

	# generator data class
	pathDataClass: %appDir%/DataClass
	constantDataClass: true
	constantSizeDataClass: true
	constantDataPrefix: 'Form'
	referencesDataClass: false
	suffixDataClass: Data
	extendsDataClass: Drago\Utils\ExtraArrayHash
	finalDataClass: false
	namespaceDataClass: App\Data

# symfony console
console:
	name: Symfony Console
	version: '1.0'
```

## Usage
Run generation commands using the Composer-installed binary:
```bash
# Generate entity classes
php vendor/bin/generator app:entity <table>

# Generate data classes
php vendor/bin/generator app:dataClass <table>
```

## Examples
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

## Features
- Generate entity and data classes from database tables
- Configurable constants and column size constants
- Support for foreign key references
- Set custom suffixes, namespaces, and final classes
- Symfony Console integration for a clean CLI

## Generator settings
All settings of entities and data form can be found in Options.php

Notes
- Designed for Nette Framework projects.
- CLI binary expects a project with app/Bootstrap.php.
- For non-Nette projects, a custom bootstrap is required.
