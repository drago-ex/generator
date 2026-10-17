<?php

/**
 * Test: Drago\Generator\Repository
 * @testCase
 */

declare(strict_types=1);

use Dibi\Reflection\Column;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class RepositoryTest extends DatabaseTestCase
{
	private function repository(): TestRepository
	{
		return new TestRepository;
	}


	public function testGetTableName(): void
	{
		Assert::equal([
			0 => 'error',
			1 => 'media',
			2 => 'test',
		], $this->repository()->mysql()->getTableNames());
	}


	public function testGetColumnsNameFromTable(): void
	{
		Assert::equal([
			0 => 'id',
			1 => 'sample',
		], $this->repository()->mysql()->getColumnNames('test'));
	}


	public function testCheckType(): void
	{
		$columnInfo = $this->repository()->mysql()->getColumn('test', 'id');

		Assert::type(
			Column::class,
			$columnInfo,
		);
	}
}


(new RepositoryTest)->run();
