<?php

declare(strict_types=1);

use Dibi\Connection;
use Dibi\Exception;
use Drago\Generator\Repository;


class TestRepository
{
	public function db(): Connect
	{
		return new Connect;
	}


	public function repository(Connection $db): Repository
	{
		return new Repository($db);
	}


	/**
	 * @throws Exception
	 */
	public function mysql(): Repository
	{
		$db = $this->db()->mysql();
		return $this->repository($db);
	}
}
