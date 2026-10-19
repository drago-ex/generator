<?php

/**
 * Test: Drago\Generator\Base
 */

declare(strict_types=1);

use Dibi\Connection;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\NoopWordInflector;
use Drago\Database\Entity;
use Drago\Generator\Base;
use Drago\Generator\Options;
use Drago\Generator\Repository;
use Drago\Generator\Type;
use Drago\Generator\ValidateColumnException;
use Drago\Utils\ExtraArrayHash;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


function createBase(): Base
{
	$inflector = new Inflector(new NoopWordInflector, new NoopWordInflector);
	$repository = new Repository(new Connection(['driver' => 'mysqli', 'lazy' => true]));
	return new Base($repository, new Options, $inflector);
}


test('Native column types are mapped to PHP property types', function () {
	$base = createBase();

	foreach ([
		'INT' => Type::Integer,
		'BIGINT' => Type::Integer,
		'TINYINT' => Type::Integer,
		'VARCHAR' => Type::Text,
		'TEXT' => Type::Text,
		'DECIMAL' => Type::Float,
		'DOUBLE' => Type::Float,
		'DATETIME' => Type::Date,
		'TIMESTAMP' => Type::Date,
		'BOOLEAN' => Type::Bool,
		'BLOB' => Type::Binary,
		'VARBINARY' => Type::Binary,
		'JSON' => Type::Text,
	] as $nativeType => $expected) {
		Assert::same($expected, $base->detectType($nativeType), $nativeType);
	}
});


test('Binary columns are generated as strings, which Dibi returns for them', function () {
	Assert::same('string', Type::Binary);
});


test('Class file names are built from table names', function () {
	$base = createBase();
	Assert::same('UsersRolesEntity', $base->filename('users_roles', 'Entity'));
	Assert::same('OrdersData', $base->filename('ORDERS', 'Data'));
});


test('Column names with parentheses are rejected', function () {
	$base = createBase();
	$base->validateColumn('test', 'sample');

	Assert::exception(
		fn() => $base->validateColumn('test', 'COUNT(id)'),
		ValidateColumnException::class,
		"Invalid column name 'COUNT(id)' in table 'test'. Use 'AS' or change the name.",
	);
});


test('Default options match the defaults of the DI extension', function () {
	$options = new Options;
	Assert::same(Entity::class, $options->extends);
	Assert::same(ExtraArrayHash::class, $options->extendsDataClass);
});
