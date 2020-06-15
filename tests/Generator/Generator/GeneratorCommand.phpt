<?php

declare(strict_types = 1);

use Drago\Generator;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function generator(): TestGenerator
{
	return new TestGenerator();
}


test(function () {
	$generatorEntity = generator()->createGeneratorEntity(generator()->repository()->mysql(), generator()->options());
	$generatorCommand = new Generator\GeneratorCommand($generatorEntity);
	Assert::type(Generator\GeneratorCommand::class, $generatorCommand);
});
