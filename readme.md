<p align="center">
  <img src="https://avatars0.githubusercontent.com/u/11717487?s=400&u=40ecb522587ebbcfe67801ccb6f11497b259f84b&v=4" width="100" alt="logo">
</p>

<h3 align="center">Drago Extension</h3>
<p align="center">Extension for Nette Framework</p>

## Drago Generator

Generating entities and form data.

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

Together options:

| Name                | Type             | Default state                | Description
| --------------------| ---------------- | -----------------------------| ------------------------------------------------- |
| `lower`             | `bool`           | `false`                      | Allow convert uppercase characters to lowercasee. |

Entity options:

| Name                | Type             | Default state                | Description
| --------------------| ---------------- | -----------------------------| --------------------------------------------- |
| `path`              | `string`         | `empty`                      | The path where the classes will be generated. |
| `constant`          | `bool`           | `true`                       | Allow constant.                               |
| `suffix`            | `string`         | `Entity`                     | Add suffix name.                              |
| `extends`           | `string`         | `Drago\Database\Entity`      | Add extends class.                            |
| `extendsOn`         | `bool`           | `true`                       | Allow extends class.                          |
| `final    `         | `bool`           | `true`                       | Add final keyword.                            |
| `namespace`         | `string`         | `App\Entity`                 | Add class namespace.                          |
| `attributeColumn`   | `bool`           | `true`                       | Allow attribute column info.                  |

Form data options:

| Name                | Type             | Default state                | Description
| --------------------| ---------------- | -----------------------------| --------------------------------------------- |
| `pathFormData`      | `string`         | `empty`                      | The path where the classes will be generated. |
| `constantFormData`  | `bool`           | `true`                       | Allow constant.                               |
| `suffixFormData`    | `string`         | `Data`                       | Add suffix name.                              |
| `extendsFormData`   | `string`         | `Drago\Utils\ExtraArrayHash` | Add extends class.                            |
| `extendFormDataOn`  | `bool`           | `true`                       | Allow extends class.                          |
| `finalFormData`     | `bool`           | `true`                       | Add final keyword.                            |
| `namespaceFormData` | `string`         | `App\Data`                   | Add class namespace.                          |

## Console commands

| Command               | Description
| --------------------- | -----------------------------------------------------|
| `make:entity`         | Generates all entities.                              |
| `make:entity table`   | Generates one entity according to the table name.    |
| `make:formData`       | Generates all form data.                             |
| `make:formData table` | Generates one form data according to the table name. |

## Prepared package for generator

[https://github.com/drago-ex/generator-cli](https://github.com/drago-ex/generator-cli)
