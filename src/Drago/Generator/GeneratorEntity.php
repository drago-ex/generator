<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Doctrine\Inflector\Inflector;
use Drago\Generator\Data\Attribute;
use Drago\Utils\CaseConverter;
use Exception;
use Nette\PhpGenerator\PhpFile;
use Nette\SmartObject;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Throwable;


/**
 * Generating an entity from database tables.
 */
class GeneratorEntity
{
	use SmartObject;

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
	 * Run generate entity.
	 * @throws Throwable
	 */
	public function runGeneration(?string $tableName = null): void
	{
		if ($tableName !== null) {

			// Generate one entity by table name.
			$this->createPhpFile($tableName);

		} else {
			foreach ($this->repository->getTableNames() as $tableName) {

				// Generate all entity.
				$this->createPhpFile($tableName);
			}
		}
	}


	/**
	 * Creating php file.
	 * @throws Throwable
	 */
	private function createPhpFile(string $tableName): void
	{
		$phpFile = new PhpFile;
		$phpFile->setStrictTypes();

		// Options for generate entity.
		$options = $this->options;

		// Helpers class.
		$helpers = $this->helpers;

		// Create an entity name from the table name and the added suffix.
		$className = $this->inflector->classify(Strings::lower($tableName)) . $options->suffix;

		// We create a entity and add namespace.
		$entity = $phpFile
			->addNamespace($options->namespace)
			->addClass($className);

		// Get all columns names from table.
		$columnsNames = $this->repository->getColumnNames($tableName);
		foreach ($columnsNames as $key => $columnName) {

			// Convert large characters to lowercase.
			if ($options->lower) {
				$columnName = Strings::lower($columnName);
			}

			// Check column names for parentheses.
			$helpers->validateColumn($tableName, $columnName);

			// Get column information.
			$column = $this->repository->getColumn($tableName, $columnName);

			// Get column type.
			$columnType = Strings::lower($helpers->detectType($column->getNativeType()));

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
				$constantName = Strings::upper(CaseConverter::snakeCase($columnName));
				$entity->addConstant($constantName, $columnName)
					->setPublic();
			}

			// Add attributes to the entity.
			$property = $entity->addProperty($columnName)
				->setVisibility($options->propertyVisibility)
				->addComment($this->getColumnQuery($tableName, $columnName))
				->addComment('@var ' . $columnType);

			// We will add the data type lde version of php.
			if (PHP_VERSION_ID > 70400) {
				$property->setType($columnType);
			} else {
				$property->addComment('@var ' . $columnType);
			}

			// Add the getter method.
			if ($options->getter) {
				$entity->addMethod('get' . $this->inflector->classify(CaseConverter::snakeCase($columnName)))
					->setVisibility('public')
					->setReturnType($columnType)
					->setReturnNullable($options->getterPrimaryNull && $column->isAutoIncrement() ? true : $column->isNullable())
					->addBody($helpers->addField($columnName, 'return $this->__FIELD__;'));
			}

			// Add the setter method.
			if ($options->setter) {
				$entity->addMethod('set' . $this->inflector->classify(CaseConverter::snakeCase($columnName)))
					->addBody($helpers->addField($columnName, '$this[\'__FIELD__\'] = $var;'))
					->setVisibility('public')
					->addParameter('var')
					->setType($columnType)
					->setNullable($column->isNullable());
			}
		}

		$file = $options->path . '/' . $className . '.php';
		FileSystem::write($file, $phpFile->__toString());
	}


	/**
	 * Column data attribute.
	 * @throws Exception
	 */
	private function getColumnAttribute(string $tableName, string $columnName): array
	{
		$column = $this->repository->getColumn($tableName, $columnName);
		return [
			Attribute::AUTO_INCREMENT => $column->autoIncrement,
			Attribute::SIZE => $column->size,
			Attribute::DEFAULT => $column->default,
			Attribute::NULLABLE => $column->nullable,
			Attribute::TYPE => Strings::lower($column->nativeType),
		];
	}


	/**
	 * @throws Exception
	 */
	private function getColumnQuery(string $tableName, string $columnName): string
	{
		$help = $this->helpers;
		$attr = $this->getColumnAttribute($tableName, $columnName);

		// Column attributes.
		$assembly = $help->getAttribute($attr, Attribute::AUTO_INCREMENT);
		$assembly .= $help->getAttribute($attr, Attribute::SIZE);
		$assembly .= $help->getAttribute($attr, Attribute::DEFAULT);
		$assembly .= $help->getAttribute($attr, Attribute::NULLABLE);
		$assembly .= $help->getAttribute($attr, Attribute::TYPE);
		return $assembly;
	}
}
