<?php

/**
 * Test: Drago\Generator\Command\EntityCommand
 */

declare(strict_types=1);

use Drago\Generator\Command\EntityCommand;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';


class EntityCommandTest extends TestCase
{
	private function generator(): TestGenerator
	{
		return new TestGenerator;
	}


	public function testEntityCommand(): void
	{
		$generator = $this->generator()->createEntityGenerator(
			$this->generator()->repository()->mysql(),
			$this->generator()->options(),
		);

		$generatorCommand = new EntityCommand($generator);

		Assert::type(
			EntityCommand::class,
			$generatorCommand,
		);
	}
}


(new EntityCommandTest)->run();
