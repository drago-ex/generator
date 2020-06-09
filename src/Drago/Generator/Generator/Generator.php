<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Doctrine\Inflector\Inflector;
use Nette;
use Nette\Utils;


/**
 * Generator entity.
 */
class Generator
{
	use Nette\SmartObject;

	/** @var string */
	private const
		NAME = 'name',
		AUTO_INCREMENT = 'autoIncrement',
		SIZE = 'length',
		DEFAULT = 'default',
		NULLABLE = 'nullable',
		PRIMARY = 'primary',
		UNIQUE = 'unique',
		TYPE = 'type';

	/** @var Repository */
	private $repository;

	/** @var Options */
	private $options;

	/** @var Inflector */
	private $inflector;


	public function __construct(Repository $repository, Options $options, Inflector $inflector)
	{
		$this->repository = $repository;
		$this->options = $options;
		$this->inflector = $inflector;
	}


	/**
	 * Run entity generate.
	 * @throws \Dibi\Exception
	 * @throws \Throwable
	 */
	public function runGenerate(?string $table = null): void
	{
		if ($table !== null) {

			// Generate one entity by table name.
			$this->createEntity($table);

		} else {
			$tables = $this->repository->getTableNames();
			foreach ($tables as $name) {

				// Generate all entity.
				$this->createEntity($name);
			}
		}
	}


	/**
	 * Creating entity.
	 * @throws \Dibi\Exception
	 * @throws \Exception
	 * @throws \Throwable
	 */
	private function createEntity(string $table): void
	{
		$php = new Nette\PhpGenerator\PhpFile;
		$php->setStrictTypes();

		// Preventive measures convert to lowercase.
		$class = Utils\Strings::lower($table);

		// Options for generate entity.
		$options = $this->options;

		// Create an entity name from the table name and the added suffix.
		$name = $this->inflector->classify($class) . $options->suffix;

		// We create a entity and add namespace.
		$entity = $php
			->addNamespace($options->namespace)
			->addClass($name);

		// Get all columns names from table.
		$columns = $this->repository->getColumns($table);
		foreach ($columns as $key => $column) {

			// Convert large characters to lowercase.
			if ($this->options->lower) {
				$columnConstant = $column;
				$column = Utils\Strings::lower($column);
			}

			// Check column names for parentheses.
			$this->addValidateColumn($table, $column);

			// Get all column information.
			$columnInfo = $this->repository->getColumnInfo($table, $column);

			// Get column type.
			$columnType = Utils\Strings::lower(Types::detectType($columnInfo->getNativeType()));

			// Add the extend and the table constant to the entity.
			$entity->setExtends($options->extends)
				->addConstant('TABLE', $table)
				->setPublic();

			// Add property annotation to entity class.
			if ($options->property) {
				$entity->addComment('@property ' . $columnType . ' $' . $column);
			}

			// Add constants to the entity.
			if ($options->constant) {
				$constant = Utils\Strings::upper($this->addSnakeCase($column));
				$entity->addConstant($constant, $columnConstant ?? $column)
					->setPublic();
			}

			// Add attributes to the entity.
			if ($options->attribute) {
				$entity->addProperty($column)
					->setVisibility($options->propertyVisibility)
					->addComment('@var ' . $columnType);

				// Add column length info.
				if ($options->attributeLength) {
					$attribute = $this->getColumnAttribute($table, $column);
					$entity->addProperty($column . 'Length', $attribute[self::SIZE])
						->addComment('@var int');
				}
			}

			// Add the getter method.
			if ($options->getter) {
				$entity->addMethod('get' . $this->inflector->classify($this->addSnakeCase($column)))
					->setVisibility('public')
					->setReturnType($columnType)
					->setReturnNullable($options->getterPrimaryNull && $columnInfo->isAutoIncrement() ? true : $columnInfo->isNullable())
					->addBody($this->addField($column, 'return $this->__FIELD__;'));
			}

			// Add the setter method.
			if ($options->setter) {
				$entity->addMethod('set' . $this->inflector->classify($this->addSnakeCase($column)))
					->addBody($this->addField($column, '$this[\'__FIELD__\'] = $var;'))
					->setVisibility('public')
					->addParameter('var')
					->setType($columnType)
					->setNullable($columnInfo->isNullable());
			}
		}

		$file = $options->path . '/' . $name . '.php';
		Utils\FileSystem::write($file, $php->__toString());
	}


	/**
	 * Replace string with the replacement string.
	 * @return mixed
	 */
	private function addField(string $replace, string $subject)
	{
		$str = str_replace('__FIELD__', $replace, $subject);
		return $str;
	}


	/**
	 * Check column names for parentheses.
	 * @throws \Exception
	 */
	private function addValidateColumn(string $table, string $column): void
	{
		if (Utils\Strings::contains($column, '(')) {
			throw new \Exception('Wrong column name ' . $column . ' in table ' .
				$table . ', change name or use AS');
		}
	}


	/**
	 * Character conversion to snake.
	 */
	private function addSnakeCase(string $input): string
	{
		if (preg_match('/[A-Z]/', $input) === 0) {
			return $input;
		}
		$pattern = '/([a-z])([A-Z])/';
		$r = strtolower(preg_replace_callback($pattern, function (array $a) {
			return $a[1] . '_' . strtolower ($a[2]);
		}, $input));
		return $r;
	}


	/**
	 * Column attribute.
	 * @throws \Dibi\Exception
	 */
	private function getColumnAttribute(string $table, string $column): array
	{
		$info = $this->repository->getColumnInfo($table, $column);
		return [
			self::NAME => $info->name,
			self::AUTO_INCREMENT => $info->autoIncrement,
			self::SIZE => $info->size,
			self::DEFAULT => $info->default,
			self::NULLABLE => $info->nullable,
			self::TYPE => Utils\Strings::lower($info->nativeType),
		];
	}


	/**
	 * Column information.
	 */
	private function getColumnInfo(array $attribute, string $key): ?string
	{
		return $attribute[$key] ? $key . '="' . $attribute[$key] . '" ' : null;
	}
}
