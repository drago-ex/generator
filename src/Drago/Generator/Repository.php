<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Drago\Database\Connect;
use Nette\Database\Context;
use Nette\Database\IStructure;
use Tracy\Debugger;


/**
 * Get table names ant types from database.
 */
class Repository
{
	private Context $db;


	public function __construct(Context $db)
	{
		$this->db = $db;
	}


	/**
	 * Get database info.
	 */
	public function getStructure(): IStructure
	{
		return $this->db->getStructure();
	}


	/**
	 * Get table information.
	 */
	public function getTable(string $name): array
	{
		$table = [];
		foreach ($this->getStructure()->getTables() as $item) {
			if ($item['name'] === $name) {
				$table[] = $item;
			}
		}
		return $table[0];
	}


	/**
	 * Get all tables names from database.
	 */
	public function getTableNames(): array
	{
		$tables = [];
		foreach ($this->getStructure()->getTables() as $table) {
			$tables[] = $table['name'];
		}
		return $tables;
	}


	/**
	 * Get all columns names from table.
	 */
	public function getColumnNames(string $table): array
	{
		$columns = [];
		foreach ($this->getStructure()->getColumns($table) as $column) {
			$columns[] = $column['name'];
		}
		return $columns;
	}


	/**
	 * Get all column information.
	 */
	public function getColumn(string $table, string $column): array
	{
		$columns = [];
		foreach ($this->getStructure()->getColumns($table) as $item) {
			if ($item['name'] === $column) {
				$columns[] = $item;
			}
		}
		return $columns[0];
	}
}
