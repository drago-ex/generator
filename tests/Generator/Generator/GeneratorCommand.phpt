<?php

declare(strict_types = 1);

namespace Test\Generator;

use Dibi\Connection;
use Drago\Generator\Generator;
use Drago\Generator\GeneratorCommand;
use Drago\Generator\Options;
use Drago\Generator\Repository;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function connect()
{
	$db = [
		'driver' => 'mysqli',
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'database' => 'test',
	];
	return new Connection($db);
}


test(function () {
	$repository = new Repository(connect());
	$generator = new Generator($repository, new Options);
	$command = new GeneratorCommand($generator);
	Assert::type(GeneratorCommand::class, $command);
});
