<?php

declare(strict_types = 1);

use Dibi\Connection;


class Connect
{
	/**
	 * @throws Dibi\Exception
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


	/**
	 * @throws Dibi\Exception
	 */
	public function oracle(): Connection
	{
		$db = [
			'driver' => 'oracle',
			'username' => 'travis',
			'password' => 'travis',
			'database' => '(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1521)))(CONNECT_DATA=(SID=xe)))',
			'charset' => 'utf8',
		];
		return new Connection($db);
	}
}
