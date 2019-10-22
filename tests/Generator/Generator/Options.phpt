<?php

declare(strict_types = 1);

namespace Test\Generator;

use Drago\Database\Entity;
use Drago\Generator\Options;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


$options = new Options;
$options->path = __DIR__ . '/path/to/entity';

Assert::type(TYPE_STRING, $options->path);
Assert::type(TYPE_STRING, $options->suffix);
Assert::same(Entity::class, $options->extends);
Assert::type(TYPE_STRING, $options->namespace);
Assert::type(TYPE_BOOL, $options->property);
Assert::type(TYPE_STRING, $options->propertyVisibility);
Assert::type(TYPE_BOOL, $options->constant);
Assert::type(TYPE_BOOL, $options->attribute);
Assert::type(TYPE_BOOL, $options->getter);
Assert::type(TYPE_BOOL, $options->setter);
Assert::type(TYPE_BOOL, $options->upper);
