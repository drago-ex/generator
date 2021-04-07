<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Drago\Utils\CaseConverter;
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
		$filename = $this->filename($table, $options->suffix);

		// Add filename and namespace.
		$create = $phpFile
			->addNamespace($options->namespace)
			->addClass($filename)
			->addTrait(SmartObject::class);

		// Add extends class.
		if ($options->extendsOn) {
			$create->setExtends($options->extends);
		}

		// Add final keyword
		if ($options->final) {
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

			// Add the constant table to the entity.
			$create->addConstant('TABLE', $table)
				->setPublic();

			/* As of PHP 7.4 it is not necessary.
			$nullable = $attr->isNullable() ? '|null' : '';
			$property = $this->detectType($attr->getNativeType()) . $nullable . ' $' . $column;
			$create->addComment('@property' . ' ' . $property);
			*/

			// Add constants to the entity.
			if ($options->constant) {
				$constant = Strings::upper(CaseConverter::snakeCase($column));
				$create->addConstant($constant, $column)
					->setPublic();

				// Add to constant column length information
				if ($options->constantLength) {
					if (!$attr->isAutoIncrement() && $attr->getSize() > 0) {
						$create->addConstant($constant . '_LENGTH', $attr->getSize());
					}
				}
			}

			// Detect native type.
			$detectType = $this->detectType($attr->getNativeType());

			// Add attributes to the entity.
			$create->addProperty($column)
				->setNullable($attr->isNullable())
				->setType($detectType)
				->setPublic();

			/* As of PHP 7.4 it is not necessary.
			// Add the setter method.
			$create->addMethod('get' . $this->inflector->classify($column))
				->addBody('return $this->?;', [$column])
				->setProtected()
				->setReturnType($detectType);

			// Add the setter method.
			$create->addMethod('set' . $this->inflector->classify($column))
				->addBody('$this->? = $?;', [$column, $column])
				->setProtected()
				->setReturnType('void')
				->addParameter($column)
				->setType($detectType)
				->setNullable($attr->isNullable());
			*/

			// Add reference to table.
			if ($options->references && isset($references[$column])) {
				$name = $this->filename($references[$column], $options->suffix);

				/* As of PHP 7.4 it is not necessary.
				$create->addComment('@property' . ' ' . $name . ' $' . $references[$column]);
				*/

				$create->addProperty($references[$column])
					->setType($options->namespace . '\\' . $name);
			}
		}

		// Generate file.
		$file = $options->path . '/' . $filename . '.php';
		FileSystem::write($file, $phpFile->__toString());
	}
}
