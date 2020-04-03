<?php

declare(strict_types = 1);

use Drago\Generator\Repository;


class TestRepository
{
	public function db(): Connect
	{
		return new Connect;
	}


	public function repository(Dibi\Connection $db): Repository
	{
		return new Repository($db);
	}


	/**
	 * @throws Dibi\Exception
	 */
	public function mysql(): Repository
	{
		$db = $this->db()->mysql();
		return $this->repository($db);
	}


	/**
	 * @throws Dibi\Exception
	 */
	public function oracle(): Repository
	{
		$db = $this->db()->oracle();
		return $this->repository($db);
	}
}
