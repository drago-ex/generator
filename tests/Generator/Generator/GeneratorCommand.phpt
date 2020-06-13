<?php

declare(strict_types = 1);

use Drago\Generator;
use Doctrine\Inflector;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function repository(): TestRepository
{
	return new TestRepository;
}


test(function () {
	$noopWordInflector = new Inflector\NoopWordInflector;
	$inflector = new Inflector\Inflector($noopWordInflector, $noopWordInflector);
	$generatorEntity = new Generator\GeneratorEntity(repository()->mysql(), new Generator\Options, $inflector, new Generator\Helpers);
	$generatorCommand = new Generator\GeneratorCommand($generatorEntity);

	Assert::type(Generator\GeneratorCommand::class, $generatorCommand);
});
