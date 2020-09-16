<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use App\Entity\RolesEntity;
use Dibi\Exception;
use Drago\Utils\CaseConverter;
use Nette\PhpGenerator\PhpFile;
use Nette\SmartObject;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Throwable;
use Tracy\Debugger;


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
			->addClass($filename);

		// Add extends class.
		if ($options->extendsOn) {
			$create->setExtends($options->extends);
		}

		// Add final keyword
		if ($options->final) {
			$create->setFinal();
		}

		// Get references.
		$references = $this->repository->getStructure()->getBelongsToReference($table);

		// Get all columns names from table.
		foreach ($this->repository->getColumnNames($table) as $column) {

			// Create class name for references.
			if (isset($references[$column])) {
				$name = $this->filename($references[$column], $options->suffix);
			}

			// Convert large characters to lowercase.
			if ($options->lower) {
				$column = Strings::lower($column);
			}

			// Check column names for parentheses.
			$this->validateColumn($table, $column);

			// Add the constant table to the entity.
			$create->addConstant('TABLE', $table);

			// Get column attribute information.
			$attr = $this->repository->getColumn($table, $column);
			if ($attr['autoincrement']) {
				$create->addConstant('ID', $attr['name']);
			}

			// Add property.
			if ($options->property) {
				$columnAttr = null;
				if ($attr['autoincrement']) {
					$columnAttr = ' {primary}';

				} elseif($attr['default']) {
					$columnAttr = ' {default ' . $attr['default'] . '}';

				} elseif ($attr['nullable']) {
					$columnAttr = ' {nullable}';
				}

				$property = $this->detectType($attr['nativetype']) . ' $' . $column;
				$create->addComment('@property' . ' ' . $property . $columnAttr);
				if (isset($references[$column])) {
					$create->addComment('@property' . ' ' . $name . ' $' . $this->normalize($column));
				}
			}

			// Add constants to the entity.
			if ($options->constant) {
				$constant = Strings::upper(CaseConverter::snakeCase($column));
				$create->addConstant($constant, $column);

				// Add to constant column length information
				if (!$attr['autoincrement'] && $attr['size'] > 0) {
					$create->addConstant($constant . '_LENGTH', $attr['size']);
				}
			}
		}

		// Generate file.
		$file = $options->path . '/' . $filename . '.php';
		FileSystem::write($file, $phpFile->__toString());
	}


	private function normalize(string $input)
	{
		$result = [];
		preg_match('/^(.*)(Id|_.*)$/', $input, $result);
		return $result[1] ?? $input;
	}
}
