<?php

declare(strict_types = 1);

use Drago\Generator;
use Doctrine\Inflector;
use Nette\Utils;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function generatorEntity(): TestGenerator
{
	return new TestGenerator;
}


function isDirectory(string $dir): void
{
	if (!is_dir($dir)) {
		Utils\FileSystem::createDir($dir);
	}
}


test(function () {
	$options = generatorEntity()->options();
	$options->path = __DIR__ . '/../../Entity';
	isDirectory($options->path);

	$generator = generatorEntity();
	$generatorEntity = $generator->testGenratorEntity($generator->repository()->mysql(), $options);
	$generatorEntity->runGeneration('test');

	Assert::exception(function () use ($generatorEntity) {
		$generatorEntity->runGeneration();
	}, Exception::class, 'Wrong column name error(...) in table error, change name or use AS');
});


test(function () {
	$options = generatorEntity()->options();
	$options->path = __DIR__ . '/../../EntityOracle';
	isDirectory($options->path);

	$generator = generatorEntity();
	$generatorEntity = $generator->testGenratorEntity($generator->repository()->oracle(), $options);
	$generatorEntity->runGeneration('TEST');
});
