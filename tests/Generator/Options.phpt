<?php

declare(strict_types = 1);

use Drago\Database\Entity;
use Drago\Utils\ExtraArrayHash;
use Tester\Assert;
use Drago\Generator;

require __DIR__ . '/../bootstrap.php';


$options = new Generator\Options;
$options->path = __DIR__ . '/path/to/entity';

// base options
Assert::type('bool', $options->constant);
Assert::type('bool', $options->primaryNull);
Assert::type('bool', $options->lower);

// generator entity
Assert::type('string', $options->path);
Assert::type('string', $options->suffix);
Assert::same(Entity::class, $options->extends);
Assert::type('string', $options->namespace);
Assert::type('bool', $options->attributeColumn);

// generator form data
Assert::type('string', $options->pathFormData);
Assert::type('string', $options->suffixFormData);
Assert::same(ExtraArrayHash::class, $options->extendsFormData);
Assert::type('string', $options->namespaceFormData);
