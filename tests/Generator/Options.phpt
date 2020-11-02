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
Assert::type('bool', $options->constantLength);
Assert::type('bool', $options->references);
Assert::type('string', $options->suffix);
Assert::same(Entity::class, $options->extends);
Assert::type('bool', $options->extendsOn);
Assert::type('bool', $options->final);
Assert::type('string', $options->namespace);

// generator form data
Assert::type('string', $options->pathDataClass);
Assert::type('bool', $options->constantDataClass);
Assert::type('bool', $options->constantLengthDataClass);
Assert::type('bool', $options->referencesDataClass);
Assert::type('string', $options->suffixDataClass);
Assert::same(Entity::class, $options->extendsDataClass);
Assert::type('bool', $options->extendsOnDataClass);
Assert::type('bool', $options->finalDataClass);
Assert::type('string', $options->namespaceDataClass);
;