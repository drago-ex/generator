<?php

declare(strict_types = 1);

namespace Test\Generator;

use Drago\Generator;
use Tester\Assert;
use Tests;

require __DIR__ . '/../../bootstrap.php';
require __DIR__ . '/../../Connect.php';


function mysql()
{
	$db = new Tests\Connect();
	return new Generator\Repository($db->mysql());
}


test(function () {
	$generator = new Generator\Generator(mysql(), new Generator\Options());
	$command = new Generator\GeneratorCommand($generator);

	Assert::type(Generator\GeneratorCommand::class, $command);
});
