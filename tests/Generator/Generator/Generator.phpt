<?php

declare(strict_types = 1);

use Dibi\Connection;
use Drago\Generator\Generator;
use Drago\Generator\Options;
use Drago\Generator\Repository;
use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';
require __DIR__ . '/../../Connect.php';


function connect(): Connect
{
	return new Connect;
}


function repository(Connection $db): Repository
{
	return new Repository($db);
}


function options(string $path): Options
{
	$options = new Options;
	$options->path = $path;
	return $options;
}


function generator(Repository $repository, Options $options): Generator
{
	return new Generator($repository, $options);
}


function isDirectory(string $dir): void
{
	if (!is_dir($dir)) {
		FileSystem::createDir($dir);
	}
}


test(function () {
	$options = options(__DIR__ . '/../../Entity');
	isDirectory($options->path);

	$generator = generator(repository(connect()->mysql()), $options);
	$generator->runGenerate('test');
	Assert::exception(function () use ($generator) {
		$generator->runGenerate();
	}, Exception::class, 'Wrong column name error(...) in table error, change name or use AS');
});


test(function () {
	$options = options(__DIR__ . '/../../EntityOracle');
	isDirectory($options->path);

	$generator = generator(repository(connect()->oracle()), $options);
	$generator->runGenerate('TEST');
});
