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
		$create = $phpFile
			->addNamespace($options->namespace)
			->addClass($this->filename($table));

		// Get all columns names from table.
		foreach ($this->repository->getColumnNames($table) as $column) {

			// Convert large characters to lowercase.
			if ($options->lower) {
				$column = Strings::lower($column);
			}

			// Check column names for parentheses.
			$this->validateColumn($table, $column);

			// Add the extends and the table constant to the entity.
			$create->setExtends($options->extendsEntity)
				->addConstant('TABLE', $table)->setPublic();

			// Add constants to the entity.
			if ($options->constant) {
				$constant = Strings::upper(CaseConverter::snakeCase($column));
				$create->addConstant($constant, $column)->setPublic();
			}

			// Get column attribute information.
			$attr = $this->attributes($this->repository->getColumn($table, $column));

			// Add attributes to the entity.
			$property = $create->addProperty($column)->setPublic()
				->setNullable($options->primaryNull && $attr[Attr::AUTO_INCREMENT] ? true : $attr[Attr::NULLABLE])
				->setType(Strings::lower($this->detectType($attr[Attr::TYPE])));

			// Column attributes.
			$autoIncrement = $this->attr($attr, Attr::AUTO_INCREMENT);
			$size = $this->attr($attr, Attr::SIZE);
			$default = $this->attr($attr, Attr::DEFAULT);
			$nullable = $this->attr($attr, Attr::NULLABLE);

			// Add columns attributes to entity.
			$options->attributeColumn
				? $property->addComment($autoIncrement . $size . $default . $nullable)
				: $create = $property;
		}

		// Generate file.
		$file = $options->path . '/' . $this->filename($table) . '.php';
		FileSystem::write($file, $phpFile->__toString());
	}
}
