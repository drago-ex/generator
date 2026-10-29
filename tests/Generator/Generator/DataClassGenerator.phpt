<?php declare(strict_types=1);

/**
 * Test: Drago\Generator\Generator\DataClassGenerator
 * @testCase
 */

use Drago\Generator\ValidateColumnException;
use Tester\Assert;
use Tester\Helpers;

require __DIR__ . '/../../bootstrap.php';


class DataClassGeneratorTest extends DatabaseTestCase
{
	private function generator(): TestGenerator
	{
		return new TestGenerator;
	}


	public function testGenerateDataClass(): void
	{
		$options = $this->generator()->options();
		$options->pathDataClass = TempDir . '/data';
		Helpers::purge($options->pathDataClass);

		$generator = $this->generator()->createDataClassGenerator(
			$this->generator()->repository()->mysql(),
			$options,
		);

		$generator->runGeneration('test');
		$generator->runGeneration('media');

		Assert::matchFile(__DIR__ . '/../expected/TestValues.php.expected', file_get_contents($options->pathDataClass . '/TestValues.php'));
		Assert::matchFile(
			__DIR__ . '/../expected/MediaValues.php.expected',
			file_get_contents($options->pathDataClass . '/MediaValues.php'),
		);
	}


	public function testGenerateDataClassWithColumnNames(): void
	{
		$options = $this->generator()->options();
		$options->pathDataClass = TempDir . '/data-snake';
		$options->camelCase = false;
		Helpers::purge($options->pathDataClass);

		$generator = $this->generator()->createDataClassGenerator(
			$this->generator()->repository()->mysql(),
			$options,
		);

		$generator->runGeneration('media');

		Assert::matchFile(
			__DIR__ . '/../expected/MediaValuesSnake.php.expected',
			file_get_contents($options->pathDataClass . '/MediaValues.php'),
		);
	}


	public function testInvalidColumnNameIsRejected(): void
	{
		$options = $this->generator()->options();
		$options->pathDataClass = TempDir . '/data-error';
		Helpers::purge($options->pathDataClass);

		$generator = $this->generator()->createDataClassGenerator(
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


(new DataClassGeneratorTest)->run();
