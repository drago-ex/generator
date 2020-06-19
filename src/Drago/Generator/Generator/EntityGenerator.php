<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Drago\Generator\Data\Attribute;
use Drago\Utils\CaseConverter;
use Exception;
use Nette\PhpGenerator\PhpFile;
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

		// Add filename and namespace.
		$phpFile = $phpFile
			->addNamespace($options->namespace)
			->addClass($this->getFilename($table));

		// Get all columns names from table.
		foreach ($this->repository->getColumnNames($table) as $column) {

			// Convert large characters to lowercase.
			if ($options->lower) {
				$column = Strings::lower($column);
			}

			// Check column names for parentheses.
			$this->validateColumn($table, $column);

			// Add the extends and the table constant to the entity.
			$phpFile->setExtends($options->extendsEntity)
				->addConstant('TABLE', $table)->setPublic();

			// Add constants to the entity.
			if ($options->constant) {
				$constant = Strings::upper(CaseConverter::snakeCase($column));
				$phpFile->addConstant($constant, $column)->setPublic();
			}

			// Get column attribute information.
			$attr = $this->repository->getColumn($table, $column);

			// Add attributes to the entity.
			$property = $phpFile->addProperty($column)->setPublic()
				->setNullable($options->primaryNull && $attr->isAutoIncrement() ? true : $attr->isNullable())
				->setType(Strings::lower($this->detectType($attr->getNativeType())));

			// Column attributes.
			$autoIncrement = 'Column autoIncrement ' . $attr->isAutoIncrement() . "\n";
			$size = 'Column size ' . $attr->getSize() . "\n";
			$default = 'Column default ' . $attr->getDefault() . "\n";
			$nullable = 'Column nullable ' . $attr->isNullable() . "\n";
			$type = 'Column type ' . $attr->getType();

			// Add columns attributes to entity.
			$options->attributeColumn
				? $property->addComment($autoIncrement . $size . $default . $nullable . $type)
				: $phpFile = $property;

			// Add table references.
			try {
				foreach ($this->getReferencesTable($table) as $reference) {
					$phpFile->addProperty($reference)
						->setType($options->namespace . '\\' . $this->getFilename($reference))
						->addComment('Reference to table.');
				}
			} catch (Exception $e) {
				// Not implemented.
			}
		}

		// Generate file.
		$file = $options->path . '/' . $this->getFilename($table) . '.php';
		FileSystem::write($file, $phpFile->__toString());
	}
}
