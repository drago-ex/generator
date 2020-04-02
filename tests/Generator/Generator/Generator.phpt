<?php

declare(strict_types = 1);

namespace Test\Generator;

use Drago\Generator;
use Nette\Utils\FileSystem;
use Tester\Assert;
use Tests;

require __DIR__ . '/../../bootstrap.php';
require __DIR__ . '/../../Connect.php';


function mysql()
{
	$db = new Tests\Connect();
	return new Generator\Repository($db->mysql());
}


function oracle()
{
	$db = new Tests\Connect();
	return new Generator\Repository($db->oracle());
}


test(function () {
	$options = new Generator\Options();
	$options->path = __DIR__ . '/../../Entity';

	if (!is_dir($options->path)) {
		FileSystem::createDir($options->path);
	}

	$generator = new Generator\Generator(mysql(), $options);
	$generator->runGenerate('test');
	Assert::exception(function () use ($generator) {
		$generator->runGenerate();
	}, \Exception::class, 'Wrong column name error(...) in table error, change name or use AS');
});


test(function () {
	$options = new Generator\Options();
	$options->path = __DIR__ . '/../../EntityOracle';

	if (!is_dir($options->path)) {
		FileSystem::createDir($options->path);
	}

	$generator = new Generator\Generator(oracle(), $options);
	$generator->runGenerate('TEST');
});
