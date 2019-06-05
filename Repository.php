<?php

declare(strict_types = 1);

/**
 * Drago Generator
 * Package built on Nette Framework
 */
namespace Drago\Generator;

use Dibi;
use Dibi\Reflection\Column;
use Dibi\Reflection\Database;
use Dibi\Reflection\Table;
use Drago\Database\Connection;

/**
 * Get table names ant types from database.
 * @package Drago\Generator\Entity
 */
class Repository extends Connection
{
	/**
	 * Get database info.
	 */
	private function getDatabaseInfo(): Database
	{
		$database = $this->db->getDatabaseInfo();
		return $database;
	}


	/**
	 * Get table information.
	 * @throws Dibi\Exception
	 */
	private function getTable(string $name): Table
	{
		$table = $this
			->getDatabaseInfo()
			->getTable($name);

		return $table;
	}


	/**
	 * Get all tables names from database.
	 */
	public function getTableNames(): array
	{
		$tables = $this
			->getDatabaseInfo()
			->getTableNames();

		return $tables;
	}


	/**
	 * Get all columns names from table.
	 * @throws Dibi\Exception
	 */
	public function getColumns(string $table): array
	{
		$columns = $this
			->getTable($table)
			->getColumnNames();

		return $columns;
	}


	/**
	 * Get all column information.
	 * @throws Dibi\Exception
	 */
	public function getColumnInfo(string $table, string $column): Column
	{
		$column = $this
			->getTable($table)
			->getColumn($column);

		return $column;
	}
}
