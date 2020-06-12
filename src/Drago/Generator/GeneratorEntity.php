<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Doctrine\Inflector;
use Drago\Generator\Data;
use Nette;
use Nette\Utils;


/**
 * Generating an entity from database tables.
 */
class GeneratorEntity
{
	use Nette\SmartObject;

	/** @var Repository */
	private $repository;

	/** @var Options */
	private $options;

	/** @var Inflector\Inflector */
	private $inflector;

	/** @var Helpers */
	private $helpers;


	public function __construct(Repository $repository, Options $options, Inflector\Inflector $inflector, Helpers $helpers)
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
	public function runGenerate(?string $tableName = null): void
	{
		if ($tableName !== null) {

			// Generate one entity by table name.
			$this->createEntity($tableName);

		} else {
			foreach ($this->repository->getTableNames() as $tableName) {

				// Generate all entity.
				$this->createEntity($tableName);
			}
		}
	}


	/**
	 * Creating entity.
	 * @throws \Dibi\Exception
	 * @throws \Exception
	 * @throws \Throwable
	 */
	private function createEntity(string $tableName): void
	{
		$phpFile = new Nette\PhpGenerator\PhpFile;
		$phpFile->setStrictTypes();

		// Preventive measures convert to lowercase.
		$className = Utils\Strings::lower($tableName);

		// Options for generate entity.
		$options = $this->options;

		// Helpers class.
		$helpers = $this->helpers;

		// Create an entity name from the table name and the added suffix.
		$entityName = $this->inflector->classify($className) . $options->suffix;

		// We create a entity and add namespace.
		$entity = $phpFile
			->addNamespace($options->namespace)
			->addClass($entityName);

		// Get all columns names from table.
		$columnsNames = $this->repository->getColumnNames($tableName);
		foreach ($columnsNames as $key => $columnName) {

			// Convert large characters to lowercase.
			if ($options->lower) {
				$columnConstant = $columnName;
				$columnName = Utils\Strings::lower($columnName);
			}

			// Check column names for parentheses.
			$helpers->validateColumn($tableName, $columnName);

			// Get column information.
			$column = $this->repository->getColumn($tableName, $columnName);

			// Get column type.
			$columnType = Utils\Strings::lower($helpers->detectType($column->getNativeType()));

			// Add the extend and the table constant to the entity.
			$entity->setExtends($options->extends)
				->addConstant('TABLE', $tableName)
				->setPublic();

			// Add property annotation to entity class.
			if ($options->property) {
				$entity->addComment('@property ' . $columnType . ' $' . $columnName);
			}

			// Add constants to the entity.
			if ($options->constant) {
				$constant = Utils\Strings::upper($helpers->snakeCase($columnName));
				$entity->addConstant($constant, $columnConstant ?? $columnName)
					->setPublic();
			}

			// Add attributes to the entity.
			$entity->addProperty($columnName)
				->setVisibility($options->propertyVisibility)
				->addComment($this->getColumnQuery($tableName, $columnName))
				->addComment('@var ' . $columnType);

			// Add the getter method.
			if ($options->getter) {
				$entity->addMethod('get' . $this->inflector->classify($helpers->snakeCase($columnName)))
					->setVisibility('public')
					->setReturnType($columnType)
					->setReturnNullable($options->getterPrimaryNull && $column->isAutoIncrement() ? true : $column->isNullable())
					->addBody($helpers->addField($columnName, 'return $this->__FIELD__;'));
			}

			// Add the setter method.
			if ($options->setter) {
				$entity->addMethod('set' . $this->inflector->classify($helpers->snakeCase($columnName)))
					->addBody($helpers->addField($columnName, '$this[\'__FIELD__\'] = $var;'))
					->setVisibility('public')
					->addParameter('var')
					->setType($columnType)
					->setNullable($column->isNullable());
			}
		}

		$file = $options->path . '/' . $entityName . '.php';
		Utils\FileSystem::write($file, $phpFile->__toString());
	}


	/**
	 * Column data attribute.
	 * @throws \Dibi\Exception
	 */
	private function getColumnAttribute(string $tableName, string $columnName): array
	{
		$column = $this->repository->getColumn($tableName, $columnName);
		return [
			Data\Attribute::AUTO_INCREMENT => $column->autoIncrement,
			Data\Attribute::SIZE => $column->size,
			Data\Attribute::DEFAULT => $column->default,
			Data\Attribute::NULLABLE => $column->nullable,
			Data\Attribute::TYPE => Nette\Utils\Strings::lower($column->nativeType),
		];
	}


	/**
	 * @throws \Dibi\Exception
	 */
	private function getColumnQuery(string $tableName, string $columnName): string
	{
		$help = $this->helpers;
		$attr = $this->getColumnAttribute($tableName, $columnName);

		// Column attributes.
		$assembly = $help->getAttribute($attr, Data\Attribute::AUTO_INCREMENT);
		$assembly .= $help->getAttribute($attr, Data\Attribute::SIZE);
		$assembly .= $help->getAttribute($attr, Data\Attribute::DEFAULT);
		$assembly .= $help->getAttribute($attr, Data\Attribute::NULLABLE);
		$assembly .= $help->getAttribute($attr, Data\Attribute::TYPE);
		return $assembly;
	}
}
