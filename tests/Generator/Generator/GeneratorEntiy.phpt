<?php

declare(strict_types = 1);

use Drago\Generator;
use Doctrine\Inflector;
use Nette\Utils;
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


function generatorEntity(
	Generator\Repository $repository,
	Generator\Options $options,
	Inflector\Inflector $inflector,
	Generator\Helpers $helpers
): Generator\GeneratorEntity
{
	return new Generator\GeneratorEntity($repository, $options, $inflector, $helpers);
}


function isDirectory(string $dir): void
{
	if (!is_dir($dir)) {
		Utils\FileSystem::createDir($dir);
	}
}


test(function () {
	$options = options(__DIR__ . '/../../Entity');
	isDirectory($options->path);

	$generatorEntity = generatorEntity(repository()->mysql(), $options, inflector(), helper());
	$generatorEntity->runGeneration('test');

	Assert::exception(function () use ($generatorEntity) {
		$generatorEntity->runGeneration();
	}, Exception::class, 'Wrong column name error(...) in table error, change name or use AS');
});


test(function () {
	$options = options(__DIR__ . '/../../EntityOracle');
	isDirectory($options->path);

	$generatorEntity = generatorEntity(repository()->oracle(), $options, inflector(), helper());
	$generatorEntity->runGeneration('TEST');
});
