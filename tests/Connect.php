<?php

declare(strict_types=1);

use Dibi\Connection;
use Dibi\Exception;


class Connect
{
	/**
	 * @throws Exception
	 */
	public function mysql(): Connection
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
}
