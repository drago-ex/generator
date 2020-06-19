<?php

declare(strict_types = 1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function generator(): TestEntityGenerator
{
	return new TestEntityGenerator;
}


function isDirectory(string $dir): void
{
	if (!is_dir($dir)) {
		FileSystem::createDir($dir);
	}
}


test(function () {
	$options = generator()->options();
	$options->path = __DIR__ . '/../../entity';
	isDirectory($options->path);

	$generator = generator();
	$generatorEntity = $generator->createGeneratorEntity($generator->repository()->mysql(), $options);
	$generatorEntity->runGeneration('test');

	Assert::exception(function () use ($generatorEntity) {
		$generatorEntity->runGeneration();
	}, Exception::class, 'Wrong column name error(...) in table error, change name or use AS');
});


test(function () {
	$options = generator()->options();
	$options->path = __DIR__ . '/../../entity-oracle';
	isDirectory($options->path);

	$generator = generator();
	$generatorEntity = $generator->createGeneratorEntity($generator->repository()->oracle(), $options);
	$generatorEntity->runGeneration('TEST');
});