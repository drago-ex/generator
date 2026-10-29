<?php declare(strict_types=1);

use Tester\TestCase;


abstract class DatabaseTestCase extends TestCase
{
	protected function setUp(): void
	{
		if (getenv('GENERATOR_DB_HOST') === false) {
			$this->skip('Set GENERATOR_DB_HOST and import the generator test schema to run database tests.');
		}
	}
}
