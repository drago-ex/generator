<?php

declare(strict_types = 1);

use Drago\Generator;
use Drago\Generator\GeneratorCommand;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function repository(): TestRepository
{
	return new TestRepository;
}


test(function () {
	$generator = new Generator\Generator(repository()->mysql(), new Generator\Options);
	$command = new GeneratorCommand($generator);

	Assert::type(GeneratorCommand::class, $command);
});
