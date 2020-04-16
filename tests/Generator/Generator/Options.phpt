<?php

declare(strict_types = 1);

use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


$options = new Drago\Generator\Options;
$options->path = __DIR__ . '/path/to/entity';

Assert::type(TYPE_STRING, $options->path);
Assert::type(TYPE_STRING, $options->suffix);
Assert::same(Drago\Database\Entity::class, $options->extends);
Assert::type(TYPE_STRING, $options->namespace);
Assert::type(TYPE_BOOL, $options->property);
Assert::type(TYPE_STRING, $options->propertyVisibility);
Assert::type(TYPE_BOOL, $options->constant);
Assert::type(TYPE_BOOL, $options->attribute);
Assert::type(TYPE_BOOL, $options->getter);
Assert::type(TYPE_BOOL, $options->getterPrimaryNull);
Assert::type(TYPE_BOOL, $options->setter);
Assert::type(TYPE_BOOL, $options->lower);
