<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Doctrine\Common\Inflector\Inflector;
use Nette\PhpGenerator\PhpFile;
use Nette\SmartObject;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;

/**
 * Generator entity.
 */
class Generator
{
	use SmartObject;

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

			// Get all table names.
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
		$php = new PhpFile;

		// Preventive measures convert to lowercase.
		$table = Strings::lower($table);

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

			// Check column names for parentheses.
			$this->addValidateColumn($table, $column);

			// Get all column information.
			$columnInfo = $this->repository->getColumnInfo($table, $column);

			// Get column type.
			$columnType = Strings::lower(Types::detectType($columnInfo->getNativeType()));

			// Add the extend and the table constant to the entity.
			$entity->setExtends($options->extends)
				->addConstant('TABLE', $options->upper ? Strings::upper($table) : $table);

			// Add property annotation to entity class.
			if ($options->property) {
				$entity->addComment('@property ' . $columnType . ' $' . $column);
			}

			// Add constants to the entity.
			if ($options->constant) {
				$constant = Strings::upper(Inflector::tableize($column));
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
				$entity->addMethod('get' . Inflector::classify($column))
					->setVisibility('public')
					->setReturnType($columnType)
					->setReturnNullable($options->getterPrimaryNull && $columnInfo->isAutoIncrement() ? true : $columnInfo->isNullable())
					->addBody($this->addField($column, 'return $this->__FIELD__;'));
			}

			// Add the setter method.
			if ($options->setter) {
				$entity->addMethod('set' . Inflector::classify($column))
					->addBody($this->addField($column, '$this[\'__FIELD__\'] = $var;'))
					->setVisibility('public')
					->addParameter('var')
					->setType($columnType)
					->setNullable($columnInfo->isNullable());
			}
		}

		$file = $options->path . '/' . $name . '.php';
		FileSystem::write($file, $php->__toString());
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
		if (Strings::contains($column, '(')) {
			throw new \Exception('Wrong column name ' . $column . ' in table ' .
				$table . ', change name or use AS');
		}
	}
}
