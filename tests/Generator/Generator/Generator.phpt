<?php

declare(strict_types = 1);

use Drago\Generator\Generator;
use Drago\Generator\Options;
use Drago\Generator\Repository;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\NoopWordInflector;
use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function repository(): TestRepository
{
	return new TestRepository;
}


function options(string $path): Options
{
	$options = new Options;
	$options->path = $path;
	return $options;
}


function inflector(): Inflector
{
	$inflector = new Inflector(new NoopWordInflector, new NoopWordInflector);
	return $inflector;
}


function generator(Repository $repository, Options $options, Inflector $inflector): Generator
{
	return new Generator($repository, $options, $inflector);
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

	$generator = generator(repository()->mysql(), $options, inflector());
	$generator->runGenerate('test');

	Assert::exception(function () use ($generator) {
		$generator->runGenerate();
	}, Exception::class, 'Wrong column name error(...) in table error, change name or use AS');
});


test(function () {
	$options = options(__DIR__ . '/../../EntityOracle');
	isDirectory($options->path);

	$generator = generator(repository()->oracle(), $options, inflector());
	$generator->runGenerate('TEST');
});
