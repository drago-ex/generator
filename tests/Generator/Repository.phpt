<?php

declare(strict_types = 1);

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
		0 => 'sampleId',
		1 => 'sampleString',
	], repository()->mysql()->getColumnNames('test'));

	$columnInfo = repository()->mysql()->getColumn('test', 'sampleId');
	Assert::type(Column::class, $columnInfo);
});


test(function () {
	Assert::equal([
		0 => 'TEST',
	], repository()->oracle()->getTableNames());

	Assert::equal([
		0 => 'SAMPLE_ID',
		1 => 'SAMPLE_STRING',
	], repository()->oracle()->getColumnNames('TEST'));

	$columnInfo = repository()->oracle()->getColumn('TEST', 'SAMPLE_ID');
	Assert::type(Column::class, $columnInfo);
});
