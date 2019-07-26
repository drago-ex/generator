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
