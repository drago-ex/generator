<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Doctrine\Inflector\Inflector;
use Drago\Generator\Data\Attributes;
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

	/** @var Inflector */
	private $inflector;

	/** @var Helpers */
	private $helpers;


	public function __construct(Repository $repository, Options $options, Inflector $inflector, Helpers $helpers)
	{
		$this->repository = $repository;
		$this->options = $options;
		$this->inflector = $inflector;
		$this->helpers = $helpers;
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
		$php->setStrictTypes();

		// Preventive measures convert to lowercase.
		$class = Utils\Strings::lower($table);

		// Options for generate entity.
		$options = $this->options;

		// Helpers class.
		$helpers = $this->helpers;

		// Create an entity name from the table name and the added suffix.
		$name = $this->inflector->classify($class) . $options->suffix;

		// We create a entity and add namespace.
		$entity = $php
			->addNamespace($options->namespace)
			->addClass($name);

		// Get all columns names from table.
		$columns = $this->repository->getColumns($table);
		foreach ($columns as $key => $column) {

			// Convert large characters to lowercase.
			if ($options->lower) {
				$columnConstant = $column;
				$column = Utils\Strings::lower($column);
			}

			// Check column names for parentheses.
			$helpers->validateColumn($table, $column);

			// Get all column information.
			$columnInfo = $this->repository->getColumnInfo($table, $column);

			// Get column type.
			$columnType = Utils\Strings::lower($helpers->detectType($columnInfo->getNativeType()));

			// Add the extend and the table constant to the entity.
			$entity->setExtends($options->extends)
				->addConstant('TABLE', $table)
				->setPublic();

			// Add property annotation to entity class.
			if ($options->property) {
				$entity->addComment('@property ' . $columnType . ' $' . $column);
			}

			// Add constants to the entity.
			if ($options->constant) {
				$constant = Utils\Strings::upper($helpers->snakeCase($column));
				$entity->addConstant($constant, $columnConstant ?? $column)
					->setPublic();
			}

			// Add attributes to the entity.
			$entity->addProperty($column)
				->setVisibility($options->propertyVisibility)
				->addComment($this->getColumnQuery($table, $column))
				->addComment('@var ' . $columnType);

			// Add the getter method.
			if ($options->getter) {
				$entity->addMethod('get' . $this->inflector->classify($helpers->snakeCase($column)))
					->setVisibility('public')
					->setReturnType($columnType)
					->setReturnNullable($options->getterPrimaryNull && $columnInfo->isAutoIncrement() ? true : $columnInfo->isNullable())
					->addBody($helpers->addField($column, 'return $this->__FIELD__;'));
			}

			// Add the setter method.
			if ($options->setter) {
				$entity->addMethod('set' . $this->inflector->classify($helpers->snakeCase($column)))
					->addBody($helpers->addField($column, '$this[\'__FIELD__\'] = $var;'))
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
	 * Column attribute.
	 * @throws \Dibi\Exception
	 */
	private function getColumnAttribute(string $table, string $column): array
	{
		$info = $this->repository->getColumnInfo($table, $column);
		return [
			Attributes::AUTO_INCREMENT => $info->autoIncrement,
			Attributes::SIZE => $info->size,
			Attributes::DEFAULT => $info->default,
			Attributes::NULLABLE => $info->nullable,
			Attributes::TYPE => Utils\Strings::lower($info->nativeType),
		];
	}


	/**
	 * @throws \Dibi\Exception
	 */
	private function getColumnQuery(string $table, string $column): string
	{
		$help = $this->helpers;
		$attr = $this->getColumnAttribute($table, $column);

		// Column attributes.
		$assembly = $help->getAttribute($attr, Attributes::AUTO_INCREMENT);
		$assembly .= $help->getAttribute($attr, Attributes::SIZE);
		$assembly .= $help->getAttribute($attr, Attributes::DEFAULT);
		$assembly .= $help->getAttribute($attr, Attributes::NULLABLE);
		$assembly .= $help->getAttribute($attr, Attributes::TYPE);
		return $assembly;
	}
}
