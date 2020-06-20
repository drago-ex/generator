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
	$options->path = __DIR__ . '/../../entity';
	isDirectory($options->path);

	$generator = generator()->createEntityGenerator(generator()->repository()->mysql(), $options);
	$generator->runGeneration('test');

	Assert::exception(function () use ($generator) {
		$generator->runGeneration();
	}, Exception::class, 'Wrong column name error(...) in table error, change name or use AS');
});


test(function () {
	$options = generator()->options();
	$options->path = __DIR__ . '/../../entity-oracle';
	isDirectory($options->path);

	$generator = generator()->createEntityGenerator(generator()->repository()->oracle(), $options);
	$generator->runGeneration('TEST');
});


test(function () {
	$options = generator()->options();
	$options->pathFormData = __DIR__ . '/../../data';
	isDirectory($options->pathFormData);

	$generator = generator()->createFormDataGenerator(generator()->repository()->mysql(), $options);
	$generator->runGeneration('test');
});


test(function () {
	$options = generator()->options();
	$options->pathFormData = __DIR__ . '/../../data-oracle';
	isDirectory($options->pathFormData);

	$generator = generator()->createFormDataGenerator(generator()->repository()->oracle(), $options);
	$generator->runGeneration('TEST');
});
