<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Dibi\Reflection\Column;
use Doctrine\Inflector\Inflector;
use Exception;
use Nette\Utils\Strings;


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
			throw new Exception('Wrong column name ' . $column . ' in table ' .
				$table . ', change name or use AS');
		}
	}


	/**
	 * Column attributes.
	 */
	public function attributes(Column $attr): array
	{
		return [
			Attribute::AUTO_INCREMENT => $attr->isAutoIncrement(),
			Attribute::SIZE => $attr->getSize(),
			Attribute::DEFAULT => $attr->getDefault(),
			Attribute::NULLABLE => $attr->isNullable(),
			Attribute::TYPE => Strings::lower($attr->nativeType),
		];
	}


	/**
	 * Info column attribute.
	 */
	public function attr(array $attr, string $key): string
	{
		return $attr[$key] ? 'Column ' . $key . ' ' . $attr[$key] . "\n" : '';
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
		} catch (\Exception $e) {
			// I don't need an announcement ...
		}
		return $ref;
	}
}
