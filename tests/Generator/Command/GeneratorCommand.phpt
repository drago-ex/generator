<?php

declare(strict_types = 1);

use Drago\Generator\Command\EntityCommand;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function generator(): TestEntityGenerator
{
	return new TestEntityGenerator;
}


test(function () {
	$generatorEntity = generator()->createGeneratorEntity(generator()->repository()->mysql(), generator()->options());
	$generatorCommand = new EntityCommand($generatorEntity);
	Assert::type(EntityCommand::class, $generatorCommand);
});
