<?php

declare(strict_types = 1);

use Drago\Generator;
use Drago\Generator\GeneratorCommand;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';
require __DIR__ . '/../../Connect.php';


function mysql()
{
	$db = new Connect;
	return new Generator\Repository($db->mysql());
}


test(function () {
	$generator = new Generator\Generator(mysql(), new Generator\Options);
	$command = new GeneratorCommand($generator);

	Assert::type(GeneratorCommand::class, $command);
});
