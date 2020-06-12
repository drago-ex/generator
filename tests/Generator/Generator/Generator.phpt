<?php

declare(strict_types = 1);

use Drago\Generator;
use Doctrine\Inflector;
use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function repository(): TestRepository
{
	return new TestRepository;
}


function options(string $path): Generator\Options
{
	$options = new Generator\Options;
	$options->path = $path;
	return $options;
}


function helper()
{
	return new Generator\Helpers;
}


function inflector(): Inflector\Inflector
{
	$noopWordInflector = new Inflector\NoopWordInflector;
	$inflector = new Inflector\Inflector($noopWordInflector, $noopWordInflector);
	return $inflector;
}


function generator(
	Generator\Repository $repository,
	Generator\Options $options,
	Inflector\Inflector $inflector,
	Generator\Helpers $helpers
	): Generator\Generator
{
	return new Generator\Generator($repository, $options, $inflector, $helpers);
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

	$generator = generator(repository()->mysql(), $options, inflector(), helper());
	$generator->runGenerate('test');

	Assert::exception(function () use ($generator) {
		$generator->runGenerate();
	}, Exception::class, 'Wrong column name error(...) in table error, change name or use AS');
});


test(function () {
	$options = options(__DIR__ . '/../../EntityOracle');
	isDirectory($options->path);

	$generator = generator(repository()->oracle(), $options, inflector(), helper());
	$generator->runGenerate('TEST');
});
