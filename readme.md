<p align="center">
  <img src="https://avatars0.githubusercontent.com/u/11717487?s=400&u=40ecb522587ebbcfe67801ccb6f11497b259f84b&v=4" width="100" alt="logo">
</p>

<h3 align="center">Drago Extension</h3>
<p align="center">Simple packages built on Nette Framework</p>

## Drago Generator

Generating entities or form data.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/generator/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fgenerator.svg)](https://badge.fury.io/ph/drago-ex%2Fgenerator)
[![Build Status](https://travis-ci.org/drago-ex/generator.svg?branch=master)](https://travis-ci.org/drago-ex/generator)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/generator/badge)](https://www.codefactor.io/repository/github/drago-ex/generator)
[![Coverage Status](https://coveralls.io/repos/github/drago-ex/generator/badge.svg?branch=master)](https://coveralls.io/github/drago-ex/generator?branch=master)

## Requirements

- PHP 7.4 or higher
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

| Name                | Type             | Default state             | description   
| --------------------| ---------------- | ------------------------- | ----------------------------------------------- |
| `constant`          | `bool`           | `true`                  | adding a constant                                 |
| `lower`             | `bool`           | `false`                 | if you have large column names, typical of Oracle |
| `path`              | `string`         | `empty`                 | the path where the entity will be generated       |
| `suffix`            | `string`         | `Entity`                | suffix entity name                                |
| `extends`           | `string`         | `Drago\Database\Entity` | extends for the entity                            |
| `namespace`         | `string`         | `App\Entity`            | namespace for entities                            |
| `attributeColumn`   | `bool`           | `true`                  | adding column information                         |
| `pathFormData`      | `string`         | `empty`                 | the path where the form data will be generated    |
| `suffixFormData`    | `string`         | `Data`                  | suffix form data name                             |
| `extendsFormData`   | `string`         | `Data`                  | extends for the form data                         |
| `namespaceFormData` | `string`         | `App\Data`              | namespace for form data                           |

## Console commands

| Command               | info   
| --------------------- | ----------------------------------------------------|
| `make:entity`         | generates all entities                              |
| `make:entity table`   | generates one entity according to the table name    |
| `make:formData`       | generates all form data                             |
| `make:formData table` | generates one form data according to the table name |

## Prepared package for generator

[https://github.com/drago-ex/generator-cli](https://github.com/drago-ex/generator-cli)
