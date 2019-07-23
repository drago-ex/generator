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

# generating entity from database
generator:
    path: %appDir%/Model/Entity
    namespace: App\Model\Entity
```

We can determine the database from which we will generate entities.

```
extensions:
    generator: Drago\Generator\DI\GeneratorExtension(@database)
```


More options can be found in: Drago\Generator\Options

## CLI

```php
#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

$bootstrap = App\Bootstrap::boot();

// Run symfony application.
$app = $bootstrap
    ->createContainer()
    ->getByType(Contributte\Console\Application::class);

// Ensure exit codes
exit($app->run());
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
