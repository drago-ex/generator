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

| Name                | Type             | Default state                | Description
| --------------------| ---------------- | -----------------------------| ------------------------------------------------------ |
| `constant`          | `bool`           | `true`                       | **Adding a constant.**                                 |
| `lower`             | `bool`           | `false`                      | **If you have large column names, typical of Oracle.** |
| `path`              | `string`         | `empty`                      | **The path where the entity will be generated.**       |
| `suffix`            | `string`         | `Entity`                     | **Suffix entity name.**                                |
| `extends`           | `string`         | `Drago\Database\Entity`      | **Extends for the entity.**                            |
| `namespace`         | `string`         | `App\Entity`                 | **Namespace for entities.**                            |
| `attributeColumn`   | `bool`           | `true`                       | **Adding column information.**                         |
| `pathFormData`      | `string`         | `empty`                      | **The path where the form data will be generated.**    |
| `suffixFormData`    | `string`         | `Data`                       | **Suffix form data name.**                             |
| `extendsFormData`   | `string`         | `Drago\Utils\ExtraArrayHash` | **Extends for the form data.**                         |
| `namespaceFormData` | `string`         | `App\Data`                   | **Namespace for form data.**                           |

## Console commands

| Command               | Description   
| --------------------- | ---------------------------------------------------------|
| `make:entity`         | **Generates all entities.**                              |
| `make:entity table`   | **Generates one entity according to the table name.**    |
| `make:formData`       | **Generates all form data.**                             |
| `make:formData table` | **Generates one form data according to the table name.** |

## Prepared package for generator

[https://github.com/drago-ex/generator-cli](https://github.com/drago-ex/generator-cli)
