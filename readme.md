## Drago Generator

Generating entities and form data.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/generator/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fgenerator.svg)](https://badge.fury.io/ph/drago-ex%2Fgenerator)
[![Tests](https://github.com/drago-ex/generator/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/generator/actions/workflows/tests.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/generator/badge)](https://www.codefactor.io/repository/github/drago-ex/generator)
[![Coverage Status](https://coveralls.io/repos/github/drago-ex/generator/badge.svg?branch=master)](https://coveralls.io/github/drago-ex/generator?branch=master)

## Requirements

- PHP 8.0 or higher
- composer

## Installation

```
composer require drago-ex/generator
```

## Extension registration

```neon
extensions:
	generator: Drago\Generator\DI\GeneratorExtension
```

## Generator settings

All settings of entities and data form can be found in Options.php

https://github.com/drago-ex/generator/blob/master/src/Drago/Generator/Options.php#L19


## Console commands

| Command                | Description
| ---------------------- | -----------------------------------------------------|
| `make:entity`          | Generates all entities.                              |
| `make:entity table`    | Generates one entity according to the table name.    |
| `make:dataClass`       | Generates all form data.                             |
| `make:dataClass table` | Generates one form data according to the table name. |

## Prepared package for generator

[https://github.com/drago-ex/generator-cli](https://github.com/drago-ex/generator-cli)
