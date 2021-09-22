<?php

/**
 * Test: Drago\Generator\Command\EntityCommand
 */

declare(strict_types=1);

use Drago\Generator\Command\EntityCommand;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';
require __DIR__ . '/../../TestGenerator.php';


function generator(): TestGenerator
{
	return new TestGenerator;
}


test('Entity command', function () {
	$generator = generator()->createEntityGenerator(generator()->repository()->mysql(), generator()->options());
	$generatorCommand = new EntityCommand($generator);
	Assert::type(EntityCommand::class, $generatorCommand);
});
