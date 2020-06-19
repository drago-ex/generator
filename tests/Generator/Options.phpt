<?php

declare(strict_types = 1);

use Drago\Database\Entity;
use Drago\Utils\ExtraArrayHash;
use Tester\Assert;
use Drago\Generator;

require __DIR__ . '/../bootstrap.php';


$options = new Generator\Options;
$options->path = __DIR__ . '/path/to/entity';

Assert::type('string', $options->path);
Assert::type('string', $options->suffix);
Assert::same(Entity::class, $options->extendsEntity);
Assert::type('string', $options->namespace);
Assert::type('bool', $options->primaryNull);
Assert::type('bool', $options->attributeColumn);
Assert::type('bool', $options->lower);

Assert::same(ExtraArrayHash::class, $options->extendsFormData);
