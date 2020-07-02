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
	 * @throws Exception
	 * @throws NotSupportedException
	 */
	public function getForeignKeys(string $table): array
	{
		$data = $this->db
			->fetch('
				SELECT ENGINE
				FROM information_schema.TABLES
				WHERE TABLE_SCHEMA = DATABASE()
				AND TABLE_NAME = ?', $table);

		if ($data['ENGINE'] !== 'InnoDB') {
			throw new NotSupportedException("Foreign keys are not supported in {$data['ENGINE']} tables.");
		}

		$res = $this->db->query('
			SELECT rc.CONSTRAINT_NAME, kcu.REFERENCED_TABLE_NAME, GROUP_CONCAT(kcu.REFERENCED_COLUMN_NAME
			ORDER BY kcu.ORDINAL_POSITION) AS REFERENCED_COLUMNS, GROUP_CONCAT(kcu.COLUMN_NAME
			ORDER BY kcu.ORDINAL_POSITION) AS COLUMNS
			FROM information_schema.REFERENTIAL_CONSTRAINTS rc
			INNER JOIN information_schema.KEY_COLUMN_USAGE kcu
				ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
				AND kcu.CONSTRAINT_SCHEMA = rc.CONSTRAINT_SCHEMA
			WHERE rc.CONSTRAINT_SCHEMA = DATABASE() AND rc.TABLE_NAME = ?
			GROUP BY rc.CONSTRAINT_NAME', $table);

		$foreignKeys = [];
		while ($row = $res->fetch()) {
			$keyName = $row['CONSTRAINT_NAME'];
			$foreignKeys[$keyName]['name'] = $keyName;
			$foreignKeys[$keyName]['local'] = explode(',', $row['COLUMNS']);
			$foreignKeys[$keyName]['table'] = $row['REFERENCED_TABLE_NAME'];
			$foreignKeys[$keyName]['foreign'] = explode(',', $row['REFERENCED_COLUMNS']);
		}
		return array_values($foreignKeys);
	}
}
