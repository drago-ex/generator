<p align="center">
  <img src="https://avatars0.githubusercontent.com/u/11717487?s=400&u=40ecb522587ebbcfe67801ccb6f11497b259f84b&v=4" width="100" alt="logo">
</p>

<h3 align="center">Drago</h3>
<p align="center">Simple packages built on Nette Framework</p>

## Info

Generating entity from database.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/drago-ex/generator/master/license.md)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fgenerator.svg)](https://badge.fury.io/ph/drago-ex%2Fgenerator)
[![CodeFactor](https://www.codefactor.io/repository/github/drago-ex/generator/badge)](https://www.codefactor.io/repository/github/drago-ex/generator)

## Requirements

- PHP 7.1 or higher
- composer

## Installation

```
composer require drago-ex/generator
```

## Configuration

```yaml
extensions:
    console: Contributte\Console\DI\ConsoleExtension(%consoleMode%)
    generator: Drago\Generator\DI\GeneratorExtension(@dibi.connection)
```

Settings for generating entities. All settings can be found in the class: **Drago\Generator\Options**

```yaml
console:
    name: Symfony Console
    catchExceptions: false
    autoExit: false
    lazy: false

generator:
    path: %appDir%/Model/Entity
    namespace: App\Model\Entity
```

## CLI

```php
#!/usr/bin/env php
<?php

declare(strict_types = 1);

require __DIR__ . '/vendor/autoload.php';

exit(App\Bootstrap::boot()
    ->createContainer()
    ->getByType(Contributte\Console\Application::class)
    ->run());
```

## Commands

Generate all tables.

```
generate:entity
```

Generate a specific table.

```
generate:entity table
```
