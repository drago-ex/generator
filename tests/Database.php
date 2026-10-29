<?php declare(strict_types=1);

use Dibi\Connection;


class Database
{
	public function mysql(): Connection
	{
		$db = [
			'driver' => 'mysqli',
			'host' => getenv('GENERATOR_DB_HOST'),
			'port' => (int) (getenv('GENERATOR_DB_PORT') ?: 3306),
			'username' => getenv('GENERATOR_DB_USER') ?: 'root',
			'password' => getenv('GENERATOR_DB_PASSWORD') ?: 'root',
			'database' => getenv('GENERATOR_DB_NAME') ?: 'test',
		];
		return new Connection($db);
	}
}
