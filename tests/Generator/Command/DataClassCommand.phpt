<?php

declare(strict_types=1);

use Drago\Generator\Command\DataClassCommand;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function generator(): TestGenerator
{
	return new TestGenerator;
}


test(function () {
	$generator = generator()->createDataClassGenerator(generator()->repository()->mysql(), generator()->options());
	$generatorCommand = new DataClassCommand($generator);
	Assert::type(DataClassCommand::class, $generatorCommand);
});
