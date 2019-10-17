<?php

declare(strict_types = 1);

namespace Test\Generator;

use Dibi\Connection;
use Dibi\Reflection\Column;
use Drago\Generator\Repository;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function connect()
{
	$db = [
		'driver' => 'mysqli',
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'database' => 'test',
	];
	return new Connection($db);
}


test(function () {
	$repository = new Repository(connect());

	Assert::equal([
		0 => 'error',
		1 => 'test',
	], $repository->getTableNames());

	Assert::equal([
		0 => 'sampleId',
		1 => 'sampleString',
	], $repository->getColumns('test'));
	Assert::type(Column::class, $repository->getColumnInfo('test', 'sampleId'));
});
