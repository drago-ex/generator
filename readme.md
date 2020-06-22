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

## Documentation

Extension registration
```neon
extensions:
	generator: Drago\Generator\DI\GeneratorExtension
```

Generator settings
```neon
generator:
	# base
	constant: true
	lower: false # You will only enable this setting if you have large column names, typical of Oralce.

	# generator entity
	path: %appDir%/entity
	suffix: Entity
	extends: Drago\Database\Entity
	namespace: App\Entity
	attributeColumn: true

	# generator form data
	pathFormData: %appDir%/data
	suffixFormData: Data
	extendsFormData: Drago\Utils\ExtraArrayHash
	namespaceFormData: App\Data
```

Console commands
- All Entity **```make:entity```** or specific table **```make:entity table```**
- All Form data **```make:formData```** or specific table **```make:formData table```**
