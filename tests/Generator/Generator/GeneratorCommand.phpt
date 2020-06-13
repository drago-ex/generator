<?php

declare(strict_types = 1);

use Drago\Generator;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function generatorEntity(): TestGenerator
{
	return new TestGenerator();
}


test(function () {
	$generatorEntity = generatorEntity()->getGeneratorEntity();
	$generatorCommand = new Generator\GeneratorCommand($generatorEntity);
	Assert::type(Generator\GeneratorCommand::class, $generatorCommand);
});
