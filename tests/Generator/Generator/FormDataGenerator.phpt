<?php

declare(strict_types = 1);

use Nette\Utils\FileSystem;

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
	$options->pathFormData = __DIR__ . '/../../data';
	isDirectory($options->pathFormData);

	$generator = generator()->createFormDataGenerator(generator()->repository()->mysql(), $options);
	$generator->runGeneration('test');
});


test(function () {
	$options = generator()->options();
	$options->path = __DIR__ . '/../../data-oracle';
	isDirectory($options->path);

	$generator = generator()->createFormDataGenerator(generator()->repository()->oracle(), $options);
	$generator->runGeneration('TEST');
});
