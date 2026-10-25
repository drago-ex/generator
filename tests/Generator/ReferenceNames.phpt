<?php

/**
 * Test: names of the reference properties
 */

declare(strict_types=1);

use Drago\Generator\Base;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test('A reference is named after the referenced table', function () {
	Assert::same(
		['category_id' => 'category'],
		Base::getReferenceNames(['category_id' => 'category'], ['id', 'category_id', 'title']),
	);
});


test('Two keys to the same table are named after their columns, so they do not collide', function () {
	Assert::same(
		['created_by_id' => 'created_by', 'updated_by_id' => 'updated_by'],
		Base::getReferenceNames(['created_by_id' => 'users', 'updated_by_id' => 'users'], ['id', 'created_by_id', 'updated_by_id']),
	);
});


test('A key whose table is also a column name gets a name that is no column', function () {
	// "author" is a column, and the key column has no "_id" to strip
	Assert::same(
		['author' => 'author_users', 'users' => 'users_users'],
		Base::getReferenceNames(['author' => 'users', 'users' => 'users'], ['id', 'author', 'users']),
	);
});
