<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Dibi\Exception;
use Dibi\NotSupportedException;
use Dibi\Reflection\Column;
use Dibi\Reflection\Database;
use Dibi\Reflection\Table;
use Drago\Database\Connect;


/**
 * Get table names ant types from database.
 */
class Repository extends Connect
{
	/**
	 * Get database info.
	 */
	private function getDatabaseInfo(): Database
	{
		return $this->db->getDatabaseInfo();
	}


	/**
	 * Get table information.
	 * @throws Exception
	 */
	public function getTable(string $name): Table
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
	 * @throws Exception
	 */
	public function getColumnNames(string $table): array
	{
		return $this->getTable($table)
			->getColumnNames();
	}


	/**
	 * Get all column information.
	 * @throws Exception
	 */
	public function getColumn(string $table, string $column): Column
	{
		return $this->getTable($table)
			->getColumn($column);
	}


	/**
	 * Returns metadata for all foreign keys in a table.
	 */
	public function getForeignKeys(string $table): array
	{
		return $this->db->getDriver()->getReflector()
			->getForeignKeys($table);
	}
}
