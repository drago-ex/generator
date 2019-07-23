## Drago Generator

Generating entity from database.

## Requirements

- PHP 7.1 or higher
- composer

## Installation

```
composer require drago-ex/generator
```

## Configuration

```
extensions:
    console: Contributte\Console\DI\ConsoleExtension(%consoleMode%)
    generator: Drago\Generator\DI\GeneratorExtension
```

We can determine the database from which we will generate entities.

```
extensions:
    generator: Drago\Generator\DI\GeneratorExtension(@database)
```

Settings for generating entities. All settings can be found in the class: **Drago\Generator\Options**

```
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
