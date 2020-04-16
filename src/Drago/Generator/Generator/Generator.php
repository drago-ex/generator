<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Doctrine\Common\Inflector\Inflector;
use Nette;
use Nette\Utils;


/**
 * Generator entity.
 */
class Generator
{
	use Nette\SmartObject;

	/** @var Repository */
	private $repository;

	/** @var Options */
	private $options;


	public function __construct(Repository $repository, Options $options)
	{
		$this->repository = $repository;
		$this->options = $options;
	}


	/**
	 * Run entity generate.
	 * @throws \Dibi\Exception
	 * @throws \Throwable
	 */
	public function runGenerate(?string $table = null): void
	{
		if ($table !== null) {

			// Generate one entity by table name.
			$this->createEntity($table);

		} else {
			$tables = $this->repository->getTableNames();
			foreach ($tables as $name) {

				// Generate all entity.
				$this->createEntity($name);
			}
		}
	}


	/**
	 * Creating entity.
	 * @throws \Dibi\Exception
	 * @throws \Exception
	 * @throws \Throwable
	 */
	private function createEntity(string $table): void
	{
		$php = new Nette\PhpGenerator\PhpFile;

		// Preventive measures convert to lowercase.
		$table = Utils\Strings::lower($table);

		// Options for generate entity.
		$options = $this->options;

		// Create an entity name from the table name and the added suffix.
		$name = Inflector::classify($table) . $options->suffix;

		// We create a entity and add namespace.
		$entity = $php
			->addNamespace($options->namespace)
			->addClass($name);

		// Get all columns names from table.
		$columns = $this->repository->getColumns($table);
		foreach ($columns as $key => $column) {

			// Convert large characters to lowercase.
			if ($this->options->lower) {
				$column = Strings::lower($column);
			}

			// Check column names for parentheses.
			$this->addValidateColumn($table, $column);

			// Get all column information.
			$columnInfo = $this->repository->getColumnInfo($table, $column);

			// Get column type.
			$columnType = Utils\Strings::lower(Types::detectType($columnInfo->getNativeType()));

			// Add the extend and the table constant to the entity.
			$entity->setExtends($options->extends)
				->addConstant('TABLE', $options->upper ? Utils\Strings::upper($table) : $table);

			// Add property annotation to entity class.
			if ($options->property) {
				$entity->addComment('@property ' . $columnType . ' $' . $column);
			}

			// Add constants to the entity.
			if ($options->constant) {
				$constant = Strings::upper($this->addSnakeCase($column));
				$entity->addConstant($constant, $column);
			}

			// Add attributes to the entity.
			if ($options->attribute) {
				$entity->addProperty($column)
					->setVisibility($options->propertyVisibility)
					->addComment('@var ' . $columnType);
			}

			// Add the getter method.
			if ($options->getter) {
				$entity->addMethod('get' . Inflector::classify($this->addSnakeCase($column)))
					->setVisibility('public')
					->setReturnType($columnType)
					->setReturnNullable($options->getterPrimaryNull && $columnInfo->isAutoIncrement() ? true : $columnInfo->isNullable())
					->addBody($this->addField($column, 'return $this->__FIELD__;'));
			}

			// Add the setter method.
			if ($options->setter) {
				$entity->addMethod('set' . Inflector::classify($this->addSnakeCase($column)))
					->addBody($this->addField($column, '$this[\'__FIELD__\'] = $var;'))
					->setVisibility('public')
					->addParameter('var')
					->setType($columnType)
					->setNullable($columnInfo->isNullable());
			}
		}

		$file = $options->path . '/' . $name . '.php';
		Utils\FileSystem::write($file, $php->__toString());
	}


	/**
	 * Replace string with the replacement string.
	 * @return mixed
	 */
	private function addField(string $replace, string $subject)
	{
		$str = str_replace('__FIELD__', $replace, $subject);
		return $str;
	}


	/**
	 * Check column names for parentheses.
	 * @throws \Exception
	 */
	private function addValidateColumn(string $table, string $column): void
	{
		if (Utils\Strings::contains($column, '(')) {
			throw new \Exception('Wrong column name ' . $column . ' in table ' .
				$table . ', change name or use AS');
		}
	}


	/**
	 * Character conversion to snake.
	 */
	private function addSnakeCase(string $input): string
	{
		if (preg_match('/[A-Z]/', $input) === 0) {
			return $input;
		}
		$pattern = '/([a-z])([A-Z])/';
		$r = strtolower(preg_replace_callback($pattern, function (array $a) {
			return $a[1] . '_' . strtolower ($a[2]);
		}, $input));
		return $r;
	}
}
