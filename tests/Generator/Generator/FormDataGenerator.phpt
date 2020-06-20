<?php

declare(strict_types = 1);

use Nette\Utils\FileSystem;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


function generator(): TestGenerator
{
	return new TestGenerator;
}


function isDirectory(string $dir): void
{
	if (!is_dir($dir)) {
		FileSystem::createDir($dir);
	}
}


test(function () {
	$options = generator()->options();
	$options->pathFormData = __DIR__ . '/../../entity';
	isDirectory($options->pathFormData);

	$generator = generator();
	$generatorEntity = $generator->createFormDataGenerator($generator->repository()->mysql(), $options);
	$generatorEntity->runGeneration('test');
});


test(function () {
	$options = generator()->options();
	$options->pathFormData = __DIR__ . '/../../entity-oracle';
	isDirectory($options->pathFormData);

	$generator = generator();
	$generatorEntity = $generator->createFormDataGenerator($generator->repository()->oracle(), $options);
	$generatorEntity->runGeneration('TEST');
});
