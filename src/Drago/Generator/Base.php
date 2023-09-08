<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Generator;

use Doctrine\Inflector\Inflector;
use Exception;
use Nette\Utils\Strings;
use Throwable;


/**
 * Base for generating php files.
 */
class Base
{
	public function __construct(
		public Repository $repository,
		public Options $options,
		public Inflector $inflector,
	) {
	}


	/**
	 * Create filename and the added suffix.
	 */
	public function filename(string $name, string $suffix): string
	{
		$filename = $this->inflector->classify(Strings::lower($name));
		return $filename . $suffix;
	}


	/**
	 * Check column names for parentheses.
	 * @throws Exception
	 */
	public function validateColumn(string $table, string $column): void
	{
		if (Strings::contains($column, '(')) {
			throw new ValidateColumnException('Wrong column name ' . $column . ' in table ' .
				$table . ', change name or use AS');
		}
	}


	/**
	 * Type detection.
	 */
	public function detectType(string $type): string
	{
		static $patterns = [
			'BYTEA|BLOB|BIN' => Type::Binary,
			'TEXT|CHAR|POINT|INTERVAL|STRING' => Type::text,
			'YEAR|BYTE|COUNTER|SERIAL|INT|LONG|SHORT' => Type::Integer,
			'CURRENCY|REAL|MONEY|FLOAT|DOUBLE|DECIMAL|NUMERIC|NUMBER' => Type::Float,
			'DATE|TIME' => Type::Date,
			'BOOL' => Type::Bool,
		];

		foreach ($patterns as $s => $val) {
			if (preg_match("#$s#i", $type)) {
				$item = $val;
			}
		}

		return $item ?? Type::text;
	}


	/**
	 * Table references.
	 */
	public function getReferencesTable(string $table): array
	{
		$ref = [];
		try {
			foreach ($this->repository->getForeignKeys($table) as $foreign) {
				$ref[$foreign['local'][0]] = $foreign['table'];
			}
		} catch (Throwable) {
			// I don't need an announcement ...
		}

		return $ref;
	}
}
