<?php

/**
 * Test: Drago\Generator\Command\DataClassCommand
 */

declare(strict_types=1);

use Drago\Generator\Command\DataClassCommand;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';
require __DIR__ . '/../../TestGenerator.php';
require __DIR__ . '/../../Database.php';
require __DIR__ . '/../../TestRepository.php';


function generator(): TestGenerator
{
	return new TestGenerator;
}


test('Data class command', function () {
	$generator = generator()->createDataClassGenerator(generator()->repository()->mysql(), generator()->options());
	$generatorCommand = new DataClassCommand($generator);
	Assert::type(DataClassCommand::class, $generatorCommand);
});
