<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Generator;

use Dibi\Connection;
use Dibi\Exception;
use Dibi\Reflection\Column;
use Dibi\Reflection\Database;
use Dibi\Reflection\Table;


/**
 * Repository class for interacting with database metadata.
 */
readonly class Repository
{
	public function __construct(
		private Connection $db,
	) {
	}


	/**
	 * Get database information.
	 *
	 * @return Database
	 */
	private function getDatabaseInfo(): Database
	{
		return $this->db->getDatabaseInfo();
	}


	/**
	 * Get table information by name.
	 *
	 * @param string $name
	 * @return Table
	 * @throws Exception
	 */
	public function getTable(string $name): Table
	{
		return $this->getDatabaseInfo()->getTable($name);
	}


	/**
	 * Get all table names in the database.
	 *
	 * @return string[]
	 */
	public function getTableNames(): array
	{
		return $this->getDatabaseInfo()->getTableNames();
	}


	/**
	 * Get all column names from a specific table.
	 *
	 * @param string $table
	 * @return string[]
	 * @throws Exception
	 */
	public function getColumnNames(string $table): array
	{
		return $this->getTable($table)->getColumnNames();
	}


	/**
	 * Get column information by table and column name.
	 *
	 * @param string $table
	 * @param string $column
	 * @return Column
	 * @throws Exception
	 */
	public function getColumn(string $table, string $column): Column
	{
		return $this->getTable($table)->getColumn($column);
	}


	/**
	 * Get foreign key metadata for a table.
	 *
	 * @param string $table
	 * @return array
	 */
	public function getForeignKeys(string $table): array
	{
		return $this->db->getDriver()->getReflector()->getForeignKeys($table);
	}
}
