<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Dibi\Reflection;
use Drago;


/**
 * Get table names ant types from database.
 */
class Repository extends Drago\Database\Connect
{
	/**
	 * Get database info.
	 */
	private function getDatabaseInfo(): Reflection\Database
	{
		$database = $this->db
			->getDatabaseInfo();

		return $database;
	}


	/**
	 * Get table information.
	 * @throws \Dibi\Exception
	 */
	private function getTable(string $name): Reflection\Table
	{
		$table = $this->getDatabaseInfo()
			->getTable($name);

		return $table;
	}


	/**
	 * Get all tables names from database.
	 */
	public function getTableNames(): array
	{
		$tables = $this->getDatabaseInfo()
			->getTableNames();

		return $tables;
	}


	/**
	 * Get all columns names from table.
	 * @throws \Dibi\Exception
	 */
	public function getColumns(string $table): array
	{
		$columns = $this->getTable($table)
			->getColumnNames();

		return $columns;
	}


	/**
	 * Get all column information.
	 * @throws \Dibi\Exception
	 */
	public function getColumnInfo(string $table, string $column): Reflection\Column
	{
		$column = $this->getTable($table)
			->getColumn($column);

		return $column;
	}
}
