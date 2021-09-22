<?php

declare(strict_types=1);

use Dibi\Connection;


class Database
{
	public function mysql(): Connection
	{
		$db = [
			'driver' => 'mysqli',
			'host' => '127.0.0.1',
			'username' => 'root',
			'password' => 'root',
			'database' => 'test',
		];
		return new Connection($db);
	}
}
