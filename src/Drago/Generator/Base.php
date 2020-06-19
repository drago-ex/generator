<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Doctrine\Inflector\Inflector;
use Drago\Generator\Data\Type;
use Exception;
use Nette\Utils\Strings;


/**
 * Base for generating php files.
 */
class Base
{
	public Options $options;
	public Inflector $inflector;
	public Repository $repository;


	public function __construct(Repository $repository, Options $options, Inflector $inflector)
	{
		$this->repository = $repository;
		$this->options = $options;
		$this->inflector = $inflector;
	}


	/**
	 * Create filename and the added suffix.
	 */
	public function getFilename(string $name): string
	{
		$filename = $this->inflector->classify(Strings::lower($name));
		return $filename . $this->options->suffix;
	}


	/**
	 * Table references.
	 * @throws Exception
	 */
	public function getReferencesTable(string $table)
	{
		$reference = [];
		foreach ($this->repository->getTable($table)->getForeignKeys() as $foreignKey) {
			$reference[] = $foreignKey->getReferences()['table'];
		}
		return $reference;
	}


	/**
	 * Check column names for parentheses.
	 * @throws Exception
	 */
	public function validateColumn(string $table, string $column): void
	{
		if (Strings::contains($column, '(')) {
			throw new Exception('Wrong column name ' . $column . ' in table ' .
				$table . ', change name or use AS');
		}
	}


	/**
	 * Type detection.
	 */
	public function detectType(string $type): string
	{
		$pattern = [
			'BYTEA|BLOB|BIN' => Type::BINARY,
			'TEXT|CHAR|POINT|INTERVAL|STRING' => Type::TEXT,
			'YEAR|BYTE|COUNTER|SERIAL|INT|LONG|SHORT' => Type::INTEGER,
			'CURRENCY|REAL|MONEY|FLOAT|DOUBLE|DECIMAL|NUMERIC|NUMBER' => Type::FLOAT,
			'BOOL|BIT' => Type::BOOL,
			'TIME' => Type::TIME,
			'DATE' => Type::DATE,
		];
		foreach ($pattern as $s => $val) {
			if (preg_match("#$s#i", $type)) {
				$item = $val;
			}
		}
		return $item ?? Type::TEXT;
	}
}
