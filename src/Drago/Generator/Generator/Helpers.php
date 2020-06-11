<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Drago\Generator\Data\Type;
use Nette\Utils\Strings;


/**
 * Auxiliary methods for the generator.
 */
class Helpers
{
	/**
	 * Replace string with the replacement string.
	 * @return mixed
	 */
	public function addField(string $replace, string $subject)
	{
		return str_replace('__FIELD__', $replace, $subject);
	}


	/**
	 * Check column names for parentheses.
	 * @throws \Exception
	 */
	public function validateColumn(string $table, string $column): void
	{
		if (Strings::contains($column, '(')) {
			throw new \Exception('Wrong column name ' . $column . ' in table ' .
				$table . ', change name or use AS');
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
	public function getAttribute(array $attribute, string $key): ?string
	{
		return $attribute[$key] ? 'Column ' .
			$key . ' = ' . $attribute[$key] . "\n" : null;
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
