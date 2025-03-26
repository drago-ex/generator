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
	 */
	private function getDatabaseInfo(): Database
	{
		return $this->db->getDatabaseInfo();
	}


	/**
	 * Get table information by name.
	 *
	 * @throws Exception
	 */
	public function getTable(string $name): Table
	{
		return $this->getDatabaseInfo()->getTable($name);
	}


	/**
	 * Get all table names in the database.
	 *
	 */
	public function getTableNames(): array
	{
		return $this->getDatabaseInfo()->getTableNames();
	}


	/**
	 * Get all column names from a specific table.
	 *
	 * @throws Exception
	 */
	public function getColumnNames(string $table): array
	{
		return $this->getTable($table)->getColumnNames();
	}


	/**
	 * Get column information by table and column name.
	 *
	 * @throws Exception
	 */
	public function getColumn(string $table, string $column): Column
	{
		return $this->getTable($table)->getColumn($column);
	}


	/**
	 * Get foreign key metadata for a table.
	 */
	public function getForeignKeys(string $table): array
	{
		return $this->db->getDriver()->getReflector()->getForeignKeys($table);
	}
}
