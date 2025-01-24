<?php

/**
 * Test: Drago\Generator\Generator\EntityGenerator
 */

declare(strict_types=1);

use Drago\Generator\ValidateColumnException;
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


test('Generate entity', function () {
	$options = generator()->options();
	$options->path = __DIR__ . '/../../entity';
	isDirectory($options->path);

	$generator = generator()->createEntityGenerator(generator()->repository()->mysql(), $options);
	$generator->runGeneration('test');

	Assert::exception(function () use ($generator) {
		$generator->runGeneration();
	}, ValidateColumnException::class, 'Invalid column name error(...) in table error. Use 'AS' or change the name.');
});
