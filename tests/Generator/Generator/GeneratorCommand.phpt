<?php

declare(strict_types = 1);

use Drago\Generator;
use Drago\Generator\GeneratorCommand;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\NoopWordInflector;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function repository(): TestRepository
{
	return new TestRepository;
}


test(function () {
	$inflector = new Inflector(new NoopWordInflector, new NoopWordInflector);
	$generator = new Generator\Generator(repository()->mysql(), new Generator\Options, $inflector);
	$command = new GeneratorCommand($generator);

	Assert::type(GeneratorCommand::class, $command);
});
