<?php

declare(strict_types = 1);

use Drago\Generator\Command\FormDataCommand;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function generator(): TestGenerator
{
	return new TestGenerator;
}


test(function () {
	$generator = generator()->createFormDataGenerator(generator()->repository()->mysql(), generator()->options());
	$generatorCommand = new FormDataCommand($generator);
	Assert::type(FormDataCommand::class, $generatorCommand);
});
