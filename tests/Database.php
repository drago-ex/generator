<?php

declare(strict_types=1);

use Dibi\Connection;


class Database
{
	public function mysql(): Connection
	{
		$db = [
			'driver' => 'mysqli',
			'host' => getenv('GENERATOR_DB_HOST'),
			'username' => getenv('GENERATOR_DB_USER') ?: 'root',
			'password' => getenv('GENERATOR_DB_PASSWORD') ?: 'root',
			'database' => getenv('GENERATOR_DB_NAME') ?: 'test',
		];
		return new Connection($db);
	}
}
