<?php

declare(strict_types = 1);

use Drago\Database\Entity;
use Drago\Utils\ExtraArrayHash;
use Tester\Assert;
use Drago\Generator;

require __DIR__ . '/../bootstrap.php';


$options = new Generator\Options;

// base options
Assert::type('bool', $options->lower);

// generator entity
Assert::type('string', $options->path);
Assert::type('bool', $options->constant);
Assert::type('string', $options->suffix);
Assert::same(Entity::class, $options->extends);
Assert::type('bool', $options->extendsOn);
Assert::type('bool', $options->final);
Assert::type('string', $options->namespace);
Assert::type('bool', $options->attributeColumn);

// generator form data
Assert::type('string', $options->pathFormData);
Assert::type('bool', $options->constantFormData);
Assert::type('string', $options->suffixFormData);
Assert::same(ExtraArrayHash::class, $options->extendsFormData);
Assert::type('bool', $options->extendFormDataOn);
Assert::type('bool', $options->finalFormData);
Assert::type('string', $options->namespaceFormData);
