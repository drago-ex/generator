<?php declare(strict_types=1);

/**
 * Test: Drago\Generator\Command\EntityCommand
 * @testCase
 */

use Drago\Generator\Command\EntityCommand;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';


class EntityCommandTest extends DatabaseTestCase
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
