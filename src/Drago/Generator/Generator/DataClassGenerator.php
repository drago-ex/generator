<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Dibi\Exception;
use Dibi\NotSupportedException;
use Drago\Utils\CaseConverter;
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
	 * @throws Throwable
	 */
	public function createPhpFile(string $table): void
	{
		$phpFile = new PhpFile;
		$phpFile->setStrictTypes();

		// Options for generator.
		$options = $this->options;

		// Create filename and add suffix.
		$filename = $this->filename($table, $options->suffixDataClass);

		// Add filename and namespace.
		$create = $phpFile
			->addNamespace($options->namespaceDataClass)
			->addClass($filename)
			->addTrait(SmartObject::class);

		// Add extends class.
		if ($options->extendsOnDataClass) {
			$create->setExtends($options->extendsDataClass);
		}

		// Add final keyword
		if ($options->finalDataClass) {
			$create->setFinal();
		}

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

			// Add constants to the entity.
			if ($options->constantDataClass) {
				$constant = Strings::upper(CaseConverter::snakeCase($column));
				$create->addConstant($constant, $column);

				// Add to constant column length information
				if ($options->constantLengthDataClass) {
					if (!$attr->isAutoIncrement() && $attr->getSize() > 0) {
						$create->addConstant($constant . '_LENGTH', $attr->getSize());
					}
				}
			}

			// Add attributes to the entity.
			$create->addProperty($column)
				->setNullable($attr->isNullable())
				->setType($this->detectType($attr->getNativeType()))
				->setPublic();

			// Add reference to table.
			if ($options->referencesDataClass && isset($references[$column])) {
				$name = $this->filename($references[$column], $options->suffixDataClass);
				$create->addProperty(Strings::firstLower($name))
					->setType($options->namespaceDataClass . '\\' . $name);
			}
		}

		// Generate file.
		$file = $options->pathDataClass . '/' . $filename . '.php';
		FileSystem::write($file, $phpFile->__toString());
	}
}
