<?php

declare(strict_types = 1);

namespace Test\Generator;

use Dibi\Reflection\Column;
use Drago\Generator\Repository;
use Tester\Assert;
use Tests\Connect;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../Connect.php';


function mysql()
{
	$db = new Connect;
	return new Repository($db->mysql());
}


function oracle()
{
	$db = new Connect;
	return new Repository($db->oracle());
}


test(function () {
	Assert::equal([
		0 => 'error',
		1 => 'test',
	], mysql()->getTableNames());

	Assert::equal([
		0 => 'sampleId',
		1 => 'sampleString',
	], mysql()->getColumns('test'));

	$columnInfo = mysql()->getColumnInfo('test', 'sampleId');
	Assert::type(Column::class, $columnInfo);
});


test(function () {
	Assert::equal([
		0 => 'TEST',
	], oracle()->getTableNames());

	Assert::equal([
		0 => 'SAMPLE_ID',
		1 => 'SAMPLE_STRING',
	], oracle()->getColumns('TEST'));

	$columnInfo = oracle()->getColumnInfo('TEST', 'SAMPLE_ID');
	Assert::type(Column::class, $columnInfo);
});