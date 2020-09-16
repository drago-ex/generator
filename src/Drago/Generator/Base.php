<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Dibi\Reflection\Column;
use Doctrine\Inflector\Inflector;
use Exception;
use Nette\Utils\Strings;
use Tracy\Debugger;


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
			throw new Exception('Wrong column name ' . $column . ' in table ' .
				$table . ', change name or use AS');
		}
	}


	/**
	 * Column attributes.
	 */
	public function attributes(array $attr): array
	{
		return [
			Attribute::AUTO_INCREMENT => $attr['autoincrement'],
			Attribute::SIZE => $attr['size'],
			Attribute::DEFAULT => $attr['default'],
			Attribute::NULLABLE => $attr['nullable'],
			Attribute::TYPE => Strings::lower($attr['nativetype']),
		];
	}


	/**
	 * Info column attribute.
	 */
	public function attr(array $attr, string $key): ?string
	{
		return $attr[$key] ? $key . ' ' . $attr[$key] : null;
	}


	/**
	 * Type detection.
	 */
	public function detectType(string $type): string
	{
		static $patterns = [
			'BYTEA|BLOB|BIN' => Type::BINARY,
			'TEXT|CHAR|POINT|INTERVAL|STRING' => Type::TEXT,
			'YEAR|BYTE|COUNTER|SERIAL|INT|LONG|SHORT' => Type::INTEGER,
			'CURRENCY|REAL|MONEY|FLOAT|DOUBLE|DECIMAL|NUMERIC|NUMBER' => Type::FLOAT,
			'DATE|TIME' => Type::DATE,
			'BOOL' => Type::BOOL,
		];

		foreach ($patterns as $s => $val) {
			if (preg_match("#$s#i", $type)) {
				$item = $val;
			}
		}
		return $item ?? Type::TEXT;
	}
}
