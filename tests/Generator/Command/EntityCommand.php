<?php

declare(strict_types=1);

use Drago\Generator\Command\EntityCommand;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function generator(): TestGenerator
{
	return new TestGenerator;
}


test(function () {
	$generator = generator()->createEntityGenerator(generator()->repository()->mysql(), generator()->options());
	$generatorCommand = new EntityCommand($generator);
	Assert::type(EntityCommand::class, $generatorCommand);
});
