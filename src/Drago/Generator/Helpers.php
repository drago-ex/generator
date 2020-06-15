<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Drago\Generator\Data\Type;
use Exception;
use Nette\Utils\Strings;


/**
 * Helpful methods for the generator.
 */
class Helpers
{
	/**
	 * Replace string with the replacement string.
	 * @return string|string[]
	 */
	public function addField(string $replace, string $subject)
	{
		return str_replace('__FIELD__', $replace, $subject);
	}


	/**
	 * Check column names for parentheses.
	 * @throws Exception
	 */
	public function validateColumn(string $tableName, string $columnName): void
	{
		if (Strings::contains($columnName, '(')) {
			throw new Exception('Wrong column name ' . $columnName . ' in table ' .
				$tableName . ', change name or use AS');
		}
	}


	/**
	 * Character conversion to snake.
	 */
	public function snakeCase(string $input): string
	{
		if (preg_match('/[A-Z]/', $input) === 0) {
			return $input;
		}
		return strtolower(preg_replace_callback('/([a-z])([A-Z])/', function (array $a) {
			return $a[1] . '_' . strtolower($a[2]);
		}, $input));
	}


	/**
	 * Column attribute information.
	 */
	public function getAttribute(array $attributes, string $key): ?string
	{
		return $attributes[$key] ? 'Column ' .
			$key . ' = ' . $attributes[$key] . "\n" : null;
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
