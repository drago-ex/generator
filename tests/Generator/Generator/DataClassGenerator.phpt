<?php

/**
 * Test: Drago\Generator\Generator\DataClassGenerator
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


test('Generate data class', function () {
	$options = generator()->options();
	$options->pathDataClass = __DIR__ . '/../../data';
	isDirectory($options->pathDataClass);

	$generator = generator()->createDataClassGenerator(generator()->repository()->mysql(), $options);
	$generator->runGeneration('test');

	Assert::exception(function () use ($generator) {
		$generator->runGeneration();
	}, ValidateColumnException::class, 'Wrong column name error(...) in table error, change name or use AS');
});
