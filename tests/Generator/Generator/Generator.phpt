<?php

declare(strict_types = 1);

namespace Test\Generator;

use Drago\Generator\Generator;
use Drago\Generator\Options;
use Drago\Generator\Repository;
use Nette\Utils\FileSystem;
use Tester\Assert;
use Tests\Connect;

require __DIR__ . '/../../bootstrap.php';
require __DIR__ . '/../../Connect.php';


function mysql()
{
	$db = new Connect;
	return new Repository($db->mysql());
}


function oracle()
{
	$db = new Connect;
	return new Repository($db->oracle());
}


test(function () {
	$options = new Options;
	$options->path = __DIR__ . '/../../Entity';

	if (!is_dir($options->path)) {
		FileSystem::createDir($options->path);
	}

	$generator = new Generator(mysql(), $options);
	$generator->runGenerate('test');
	Assert::exception(function () use ($generator) {
		$generator->runGenerate();
	}, \Exception::class, 'Wrong column name error(...) in table error, change name or use AS');
});


test(function () {
	$options = new Options;
	$options->path = __DIR__ . '/../../EntityOracle';

	if (!is_dir($options->path)) {
		FileSystem::createDir($options->path);
	}

	$generator = new Generator(oracle(), $options);
	$generator->runGenerate('TEST');
});
