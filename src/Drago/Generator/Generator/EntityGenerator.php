<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Generator;

use Dibi\Exception;
use Drago\Utils\CaseConverter;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\SmartObject;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Throwable;


/**
 * Generating entity class.
 */
class EntityGenerator extends Base implements IGenerator
{
	/**
	 * Run generate.
	 * @throws Exception
	 * @throws Throwable
	 */
	public function runGeneration(null|string $table = null): void
	{
		if ($table !== null) {
			$this->createPhpFile($table);
		} else {
			foreach ($this->repository->getTableNames() as $table) {
				$this->createPhpFile($table);
			}
		}
	}


	/**
	 * Creating php file.
	 * @throws Exception
	 * @throws Throwable
	 */
	public function createPhpFile(string $table): void
	{
		// Options for generator.
		$options = $this->options;

		// Create filename and add suffix.
		$name = $this->filename($table, $options->suffix);

		// Generating a class.
		$class = new ClassType($name);

		// Add final keyword
		if ($options->final) {
			$class->setFinal();
		}

		// Add extends class.
		if ($options->extendsOn) {
			$class->setExtends($options->extends);
		}

		// Add Nette SmartObject.
		$class->addTrait(SmartObject::class);

		// Get references.
		$references = $this->getReferencesTable($table);

		// Get all columns names from table.
		foreach ($this->repository->getColumnNames($table) as $column) {

			// Convert large characters to lowercase.
			if ($options->lower) {
				$column = Strings::lower($column);
			}

			// Check column names for parentheses.
			$this->validateColumn($table, $column);

			// Get column attribute information.
			$attr = $this->repository->getColumn($table, $column);

			// Add the constant table.
			$tableName = $this->options->tableName ?? 'TABLE';
			$class->addConstant($tableName, $table)
				->setPublic();

			// Add the constant primary key.
			if ($attr->isAutoIncrement()) {
				$primaryKey = $this->options->primaryKey ?? 'PRIMARY';
				$class->addConstant($primaryKey, $column)
					->setPublic();
			}

			// Add other constants.
			if ($options->constant) {
				$constant = Strings::upper(CaseConverter::snakeCase($column));
				if (!$attr->isAutoIncrement()) {
					$class->addConstant($constant, $column)
						->setPublic();
				}

				// Add to constant column length information
				if ($options->constantLength) {
					if (!$attr->isAutoIncrement() && $attr->getSize() > 0) {
						$class->addConstant($constant . '_LENGTH', $attr->getSize())
							->setPublic();
					}
				}
			}

			// Detect native type.
			$detectType = $this->detectType($attr->getNativeType());

			// Add attributes to the entity.
			if ($attr->isAutoIncrement()) {
				$create = $class->addProperty($column)
					->setType($detectType)
					->setNullable(true)
					->setPublic();

			} else {
				$create = $class->addProperty($column)
					->setType($detectType)
					->setNullable($attr->isNullable())
					->setInitialized($attr->isNullable() ?? false)
					->setPublic();
			}

			// Add basic column info.
			if ($this->options->columnInfo) {
				if ($attr->isAutoIncrement()) {
					$create->addComment('Primary key');
				}

				if ($attr->getDefault()) {
					$create->addComment('Default value ' . $attr->getDefault());
				}

				if ($attr->getSize() > 0) {
					$create->addComment('Column length ' . $attr->getSize());
				}
			}

			// Add reference to table.
			if ($options->references && isset($references[$column])) {
				$filename = $this->filename($references[$column], $options->suffix);
				$class->addProperty($references[$column])
					->setType($options->namespace . '\\' . $filename)
					->setComment('Table join references');
			}
		}

		// Generate PHP file.
		$file = new PhpFile;
		$file->addComment('This file was generated by Drago Generator.')
			->setStrictTypes()
			->addNamespace($options->namespace)
			->addUse('Drago')
			->addUse('Nette')
			->add($class);

		$path = $options->path . '/' . $name . '.php';
		FileSystem::write($path, $file->__toString());
	}
}
