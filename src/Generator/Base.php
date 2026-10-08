<?php

declare(strict_types=1);

namespace Drago\Generator;

use Doctrine\Inflector\Inflector;
use Nette\Utils\Strings;
use Throwable;


class Base
{
	public function __construct(
		public Repository $repository,
		public Options $options,
		public Inflector $inflector,
	) {
	}


	public function filename(string $name, string $suffix): string
	{
		$filename = $this->inflector->classify(Strings::lower($name));
		return $filename . $suffix;
	}


	/**
	 * Validates a column name and checks for parentheses.
	 * @throws ValidateColumnException
	 */
	public function validateColumn(string $table, string $column): void
	{
		if (str_contains($column, '(')) {
			throw new ValidateColumnException("Invalid column name '$column' in table '$table'. Use 'AS' or change the name.");
		}
	}


	public function detectType(string $type): string
	{
		static $patterns = [
			'BYTEA|BLOB|BIN' => Type::Binary,
			'TEXT|CHAR|STRING|POINT|INTERVAL' => Type::Text,
			'YEAR|INT|LONG|SHORT|COUNTER|SERIAL|BYTE' => Type::Integer,
			'CURRENCY|MONEY|REAL|FLOAT|DOUBLE|DECIMAL|NUMERIC|NUMBER' => Type::Float,
			'DATE|TIME' => Type::Date,
			'BOOL' => Type::Bool,
		];

		foreach ($patterns as $pattern => $typeConstant) {
			if (preg_match("#$pattern#i", $type)) {
				return $typeConstant;
			}
		}

		return Type::Text;
	}


	/**
	 * Returns foreign key references for the table.
	 * @return array<string, string>
	 */
	public function getReferencesTable(string $table): array
	{
		$ref = [];
		try {
			foreach ($this->repository->getForeignKeys($table) as $foreign) {
				if (!isset($foreign['local'], $foreign['table'])) {
					continue;
				}
				$ref[$foreign['local'][0]] = (string) $foreign['table'];
			}
		} catch (Throwable $e) {
			// Silent fail: no need to report errors
		}
		return $ref;
	}
}
