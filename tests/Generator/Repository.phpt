<?php

declare(strict_types=1);

use Dibi\Reflection\Column;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function repository(): TestRepository
{
	return new TestRepository;
}


test(function () {
	Assert::equal([
		0 => 'error',
		1 => 'test',
	], repository()->mysql()->getTableNames());

	Assert::equal([
		0 => 'id',
		1 => 'sample',
	], repository()->mysql()->getColumnNames('test'));

	$columnInfo = repository()->mysql()->getColumn('test', 'id');
	Assert::type(Column::class, $columnInfo);
});
