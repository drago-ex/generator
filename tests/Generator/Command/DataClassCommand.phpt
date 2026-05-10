<?php

/**
 * Test: Drago\Generator\Command\DataClassCommand
 */

declare(strict_types=1);

use Drago\Generator\Command\DataClassCommand;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';


class DataClassCommandTest extends TestCase
{
	private function generator(): TestGenerator
	{
		return new TestGenerator;
	}


	public function testDataClassCommand(): void
	{
		$generator = $this->generator()->createDataClassGenerator(
			$this->generator()->repository()->mysql(),
			$this->generator()->options(),
		);

		$generatorCommand = new DataClassCommand($generator);

		Assert::type(
			DataClassCommand::class,
			$generatorCommand,
		);
	}
}


(new DataClassCommandTest)->run();
