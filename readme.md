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
    generator: Drago\Generator\DI\GeneratorExtension

# entity generator
generator:
    path: %appDir%/Model/Entity
    namespace: App\Model\Entity
```

More options can be found in: Drago\Generator\Options
