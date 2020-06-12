<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Dibi\Reflection;
use Drago\Database;


/**
 * Get table names ant types from database.
 */
class Repository extends Database\Connect
{
	/**
	 * Get database info.
	 */
	private function getDatabaseInfo(): Reflection\Database
	{
		return $this->db->getDatabaseInfo();
	}


	/**
	 * Get table information.
	 * @throws \Dibi\Exception
	 */
	public function getTable(string $name): Reflection\Table
	{
		return $this->getDatabaseInfo()
			->getTable($name);
	}


	/**
	 * Get all tables names from database.
	 */
	public function getTableNames(): array
	{
		return $this->getDatabaseInfo()
			->getTableNames();
	}


	/**
	 * Get all columns names from table.
	 * @throws \Dibi\Exception
	 */
	public function getColumnNames(string $table): array
	{
		return $this->getTable($table)
			->getColumnNames();
	}


	/**
	 * Get all column information.
	 * @throws \Dibi\Exception
	 */
	public function getColumn(string $table, string $column): Reflection\Column
	{
		return $this->getTable($table)
			->getColumn($column);
	}
}
