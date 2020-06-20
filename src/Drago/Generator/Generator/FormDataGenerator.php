<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Drago\Utils\CaseConverter;
use Exception;
use Nette\PhpGenerator\PhpFile;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Throwable;


/**
 * Generating form data.
 */
class FormDataGenerator extends Base implements IGenerator
{
	/**
	 * Run generate.
	 * @throws Throwable
	 */
	public function runGeneration(?string $table)
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
	 * Creating php file
	 * @throws Throwable
	 */
	public function createPhpFile(string $table)
	{
		$phpFile = new PhpFile;
		$phpFile->setStrictTypes();

		// Options for generator.
		$options = $this->options;

		// Create filename and add suffix.
		$filename = $this->filename($table, $options->suffixFormData);

		// Add filename and namespace.
		$create = $phpFile
			->addNamespace($options->namespaceFormData)
			->addClass($filename)
			->setExtends($options->extendsFormData);

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
			if ($options->constant) {
				$constant = Strings::upper(CaseConverter::snakeCase($column));
				$create->addConstant($constant, $column)
					->setPublic();

				// Add to constant column length information
				$create->addConstant($constant . '_LENGTH', $attr->getSize())
					->setPublic();
			}

			// Add attributes to the entity.
			$create->addProperty($column)
				->setNullable($options->primaryNull && $attr->isAutoIncrement() ? true : $attr->isNullable())
				->setType(Strings::lower($this->detectType($attr->getNativeType())))
				->setPublic();

			// Add reference to table.
			foreach ($this->getReferencesTable($table) as $reference) {
				$create->addProperty($reference)
					->setType($options->namespaceFormData . '\\' . $this->filename($reference, $options->suffixFormData))
					->setPublic();
			}
		}

		// Generate file.
		$file = $options->pathFormData . '/' . $filename . '.php';
		FileSystem::write($file, $phpFile->__toString());
	}


	/**
	 * Detect table references.
	 */
	private function getReferencesTable(string $table)
	{
		$references = [];
		try {
			foreach ($this->repository->getTable($table)->getForeignKeys() as $foreignKey) {
				$references[] = $foreignKey->getReferences()['table'];
			}
		} catch (Exception $e) {
			// Not implement.
		}
		return $references;
	}
}
