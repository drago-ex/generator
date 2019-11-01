<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

/**
 * Data type converter for php.
 */
class Types
{
	public const
		TEXT = 'string',
		BINARY = 'resource',
		BOOL = 'bool',
		INTEGER = 'int',
		FLOAT = 'float',
		DATE = '\DateTime',
		TIME = '\DateInterval';


	public static function detectType(string $type): ?string
	{
		$pattern = [
			'BYTEA|BLOB|BIN' => self::BINARY,
			'TEXT|CHAR|POINT|INTERVAL|STRING' => self::TEXT,
			'YEAR|BYTE|COUNTER|SERIAL|INT|LONG|SHORT' => self::INTEGER,
			'CURRENCY|REAL|MONEY|FLOAT|DOUBLE|DECIMAL|NUMERIC|NUMBER' => self::FLOAT,
			'BOOL|BIT' => self::BOOL,
			'TIME' => self::TIME,
			'DATE' => self::DATE,
		];
		foreach ($pattern as $s => $val) {
			if (preg_match("#$s#i", $type)) {
				$item = $val;
			}
		}
		return isset($item) ? $item : null;
	}
}
