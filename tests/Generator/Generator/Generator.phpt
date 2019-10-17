<?php

declare(strict_types = 1);

namespace Test\Generator;

use Dibi\Connection;
use Drago\Generator\Generator;
use Drago\Generator\Options;
use Drago\Generator\Repository;
use Nette\Utils\FileSystem;
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
	$options = new Options;
	$options->path = __DIR__ . '/../../Entity';

	if (!is_dir($options->path)) {
		FileSystem::createDir($options->path);
	}

	$generator = new Generator($repository, $options);
	Assert::null($generator->runGenerate('test'));
	Assert::exception(function () use ($generator) {
		$generator->runGenerate();
	}, \Exception::class, 'Bad column name error(...) for table error, please change the name or use AS');
});
