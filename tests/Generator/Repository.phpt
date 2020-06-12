<?php

declare(strict_types = 1);

use Dibi\Reflection;
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
	], repository()->mysql()->getColumns('test'));

	$columnInfo = repository()->mysql()->getColumnInfo('test', 'sampleId');
	Assert::type(Reflection\Column::class, $columnInfo);
});


test(function () {
	Assert::equal([
		0 => 'TEST',
	], repository()->oracle()->getTableNames());

	Assert::equal([
		0 => 'SAMPLE_ID',
		1 => 'SAMPLE_STRING',
	], repository()->oracle()->getColumns('TEST'));

	$columnInfo = repository()->oracle()->getColumnInfo('TEST', 'SAMPLE_ID');
	Assert::type(Reflection\Column::class, $columnInfo);
});
