<?php declare(strict_types=1);

/**
 * Test: Drago\Generator\Generator\EntityGenerator
 * @testCase
 */

use Drago\Generator\ValidateColumnException;
use Tester\Assert;
use Tester\Helpers;

require __DIR__ . '/../../bootstrap.php';


class EntityGeneratorTest extends DatabaseTestCase
{
	private function generator(): TestGenerator
	{
		return new TestGenerator;
	}


	public function testGenerateEntity(): void
	{
		$options = $this->generator()->options();
		$options->path = TempDir . '/entity';
		Helpers::purge($options->path);

		$generator = $this->generator()->createEntityGenerator(
			$this->generator()->repository()->mysql(),
			$options,
		);

		$generator->runGeneration('test');
		$generator->runGeneration('media');

		Assert::matchFile(__DIR__ . '/../expected/TestEntity.php.expected', file_get_contents($options->path . '/TestEntity.php'));
		Assert::matchFile(__DIR__ . '/../expected/MediaEntity.php.expected', file_get_contents($options->path . '/MediaEntity.php'));
	}


	public function testGenerateEntityWithColumnNames(): void
	{
		$options = $this->generator()->options();
		$options->path = TempDir . '/entity-snake';
		$options->camelCase = false;
		Helpers::purge($options->path);

		$generator = $this->generator()->createEntityGenerator(
			$this->generator()->repository()->mysql(),
			$options,
		);

		$generator->runGeneration('media');

		Assert::matchFile(__DIR__ . '/../expected/MediaEntitySnake.php.expected', file_get_contents($options->path . '/MediaEntity.php'));
	}


	public function testInvalidColumnNameIsRejected(): void
	{
		$options = $this->generator()->options();
		$options->path = TempDir . '/entity-error';
		Helpers::purge($options->path);

		$generator = $this->generator()->createEntityGenerator(
			$this->generator()->repository()->mysql(),
			$options,
		);

		Assert::exception(
			fn() => $generator->runGeneration('error'),
			ValidateColumnException::class,
			"Invalid column name 'error(...)' in table 'error'. Use 'AS' or change the name.",
		);
	}
}


(new EntityGeneratorTest)->run();
