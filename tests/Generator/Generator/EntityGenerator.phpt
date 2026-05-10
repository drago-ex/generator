<?php

/**
 * Test: Drago\Generator\Generator\EntityGenerator
 */

declare(strict_types=1);

use Drago\Generator\ValidateColumnException;
use Nette\Utils\FileSystem;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';


class EntityGeneratorTest extends TestCase
{
	private function generator(): TestGenerator
	{
		return new TestGenerator;
	}


	private function isDirectory(string $dir): void
	{
		if (!is_dir($dir)) {
			FileSystem::createDir($dir);
		}
	}


	public function testGenerateEntity(): void
	{
		$options = $this->generator()->options();
		$options->path = __DIR__ . '/../../entity';

		$this->isDirectory($options->path);

		$generator = $this->generator()->createEntityGenerator(
			$this->generator()->repository()->mysql(),
			$options,
		);

		$generator->runGeneration('test');

		Assert::exception(
			function () use ($generator): void {
				$generator->runGeneration();
			},
			ValidateColumnException::class,
			"Invalid column name 'error(...)' in table 'error'. Use 'AS' or change the name.",
		);
	}
}


(new EntityGeneratorTest)->run();
