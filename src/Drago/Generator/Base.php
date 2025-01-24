<?php

declare(strict_types=1);

namespace Drago\Generator;

use Doctrine\Inflector\Inflector;
use Nette\Utils\Strings;
use Throwable;


/**
 * Base class for PHP code generation related to database entities and data classes.
 * Provides utility methods for generating file names, validating columns, detecting types,
 * and handling table references, used by the generators for creating entity and data class files.
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
	 * Create filename by adding the suffix.
	 */
	public function filename(string $name, string $suffix): string
	{
		$filename = $this->inflector->classify(Strings::lower($name));
		return $filename . $suffix;
	}


	/**
	 * Validate column name, check for parentheses.
	 * @throws ValidateColumnException
	 */
	public function validateColumn(string $table, string $column): void
	{
		if (str_contains($column, '(')) {
			throw new ValidateColumnException("Invalid column name '$column' in table '$table'. Use 'AS' or change the name.");
		}
	}


	/**
	 * Detect the column's native type.
	 */
	public function detectType(string $type): string
	{
		static $patterns = [
			'BYTEA|BLOB|BIN' => Type::Binary,
			'TEXT|CHAR|STRING' => Type::Text,
			'YEAR|INT|LONG' => Type::Integer,
			'CURRENCY|MONEY' => Type::Float,
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
	 * Get foreign key references for the table.
	 */
	public function getReferencesTable(string $table): array
	{
		$ref = [];
		try {
			foreach ($this->repository->getForeignKeys($table) as $foreign) {
				$ref[$foreign['local'][0]] = $foreign['table'];
			}
		} catch (Throwable $e) {
			// Silent fail: no need to report errors
		}
		return $ref;
	}
}
