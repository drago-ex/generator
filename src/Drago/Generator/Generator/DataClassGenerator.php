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
 * Generating data class.
 */
class DataClassGenerator extends Base implements IGenerator
{
	/**
	 * Run generate.
	 * @throws Exception
	 * @throws Throwable
	 */
	public function runGeneration(?string $table = null): void
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
		$name = $this->filename($table, $options->suffixDataClass);

		// Generating a class.
		$class = new ClassType($name);

		// Add final keyword
		if ($options->finalDataClass) {
			$class->setFinal();
		}

		// Add extends class.
		if ($options->extendsOn) {
			$class->setExtends($options->extendsDataClass);
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

			// Add constants.
			if ($options->constantDataClass) {
				$constant = Strings::upper(CaseConverter::snakeCase($column));
				$class->addConstant($constant, $column)
					->setPublic();

				// Add to constant column length information.
				if ($options->constantLengthDataClass) {
					if (!$attr->isAutoIncrement() && $attr->getSize() > 0) {
						$class->addConstant($constant . '_LENGTH', $attr->getSize())
							->setPublic();
					}
				}
			}

			// Detect native type.
			$detectType = $this->detectType($attr->getNativeType());

			// Add attributes to the entity.
			$class->addProperty($column)
				->setType($detectType)
				->setNullable($attr->isNullable())
				->setInitialized($attr->isNullable() ?? false)
				->setPublic();

			// Add reference to table.
			if ($options->referencesDataClass && isset($references[$column])) {
				$filename = $this->filename($references[$column], $options->suffixDataClass);
				$class->addProperty($references[$column])
					->setType($options->namespaceDataClass . '\\' . $filename);
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

		$path = $options->pathDataClass . '/' . $name . '.php';
		FileSystem::write($path, $file->__toString());
	}
}
