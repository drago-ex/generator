<?php declare(strict_types=1);

namespace Drago\Generator;

use Dibi\NotImplementedException;
use Dibi\NotSupportedException;
use Doctrine\Inflector\Inflector;
use Drago\Utils\CaseConverter;
use Nette\Utils\Strings;


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


	/** Name of the property of a column (or of a reference), in camelCase unless the option turns it off. */
	public function propertyName(string $column): string
	{
		return $this->options->camelCase ? CaseConverter::camelCase($column) : $column;
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
		} catch (NotSupportedException|NotImplementedException) {
			// the driver cannot read foreign keys (Oracle, SQL Server and ODBC throw NotImplementedException),
			// the classes are generated without references
		}
		return $ref;
	}


	/**
	 * Names of the reference properties by the foreign key column: the referenced table, or the column without
	 * "_id" when the table is referenced more than once or is also the name of a column, so no two properties
	 * share a name (two keys to users, e.g. created_by_id and updated_by_id, give created_by and updated_by).
	 * @param array<string, string> $references referenced table by the foreign key column
	 * @param list<string> $columns
	 * @return array<string, string>
	 */
	public static function getReferenceNames(array $references, array $columns): array
	{
		$counts = array_count_values($references);
		$names = [];
		foreach ($references as $column => $table) {
			$name = $table;
			if ($counts[$table] > 1 || in_array($name, $columns, true) || in_array($name, $names, true)) {
				$name = (string) preg_replace('~_id$~', '', $column);
				if ($name === $column || in_array($name, $columns, true) || in_array($name, $names, true)) {
					$name = $column . '_' . $table;
				}
			}

			$names[$column] = $name;
		}

		return $names;
	}
}
