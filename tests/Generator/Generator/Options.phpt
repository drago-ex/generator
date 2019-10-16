<?php

declare(strict_types = 1);

namespace Test\Generator;

use Drago\Database\Entity;
use Drago\Generator\Options;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


$options = new Options;
$options->path = __DIR__ . '/path/to/entity';

Assert::type('string', $options->path);
Assert::type('string', $options->suffix);
Assert::same(Entity::class, $options->extends);
Assert::type('string', $options->namespace);
Assert::type('bool', $options->property);
Assert::type('bool', $options->constant);
Assert::type('bool', $options->attribute);
Assert::type('bool', $options->getter);
Assert::type('bool', $options->setter);
Assert::type('bool', $options->upper);
