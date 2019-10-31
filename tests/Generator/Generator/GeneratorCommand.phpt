<?php

declare(strict_types = 1);

namespace Test\Generator;

use Drago\Generator\Generator;
use Drago\Generator\GeneratorCommand;
use Drago\Generator\Options;
use Drago\Generator\Repository;
use Tester\Assert;
use Tests\Connect;

require __DIR__ . '/../../bootstrap.php';
require __DIR__ . '/../../Connect.php';


function mysql()
{
	$db = new Connect;
	return new Repository($db->mysql());
}


test(function () {
	$generator = new Generator(mysql(), new Options);
	$command = new GeneratorCommand($generator);

	Assert::type(GeneratorCommand::class, $command);
});
