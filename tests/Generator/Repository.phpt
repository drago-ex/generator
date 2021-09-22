<?php

/**
 * Test: Drago\Generator\Repository
 */

declare(strict_types=1);

use Dibi\Reflection\Column;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../TestRepository.php';
require __DIR__ . '/../Connect.php';


function repository(): TestRepository
{
	return new TestRepository;
}


test('Get table name', function () {
	Assert::equal([
		0 => 'error',
		1 => 'test',
	], repository()->mysql()->getTableNames());
});


test('Get columns name from table', function () {
	Assert::equal([
		0 => 'id',
		1 => 'sample',
	], repository()->mysql()->getColumnNames('test'));
});


test('Check type', function () {
	$columnInfo = repository()->mysql()->getColumn('test', 'id');
	Assert::type(Column::class, $columnInfo);
});
