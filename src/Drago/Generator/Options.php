<?php

declare(strict_types=1);

namespace Drago\Generator;

use Dibi\Row;
use Nette\Utils\ArrayHash;


/** Options for generating PHP files. */
class Options
{
	/** Allow converting uppercase to lowercase keys in arrays (typical of Oracle). */
	public bool $lower = false;

	/** The path where the classes will be generated. */
	public string $path = '';

	/** Custom name for the table in constant. */
	public ?string $tableName = null;

	/** Custom name for the primary key in the table at the constant. */
	public ?string $primaryKey = null;

	/** Allow constant. */
	public bool $constant = true;

	/** Allow basic column info. */
	public bool $columnInfo = false;

	/** Add the name before the constant. */
	public ?string $constantPrefix = null;

	/** Allow constant column size. */
	public bool $constantSize = false;

	/** Allow table references in the property. */
	public bool $references = false;

	/** Add suffix name. */
	public string $suffix = 'Entity';

	/** Allow extending a class. */
	public bool $extendsOn = true;

	/** Add extends class. */
	public string $extends = Row::class;

	/** Add final keyword. */
	public bool $final = false;

	/** Add class namespace. */
	public string $namespace = 'App\Entity';

	/** The path where the classes will be generated. */
	public string $pathDataClass = '';

	/** Allow constant in data class. */
	public bool $constantDataClass = true;

	/** Allow constant column size in data class. */
	public bool $constantSizeDataClass = true;

	/** Add the name before the constant in data class. */
	public ?string $constantDataPrefix = null;

	/** Allow table references in the property in data class. */
	public bool $referencesDataClass = false;

	/** Add suffix name in data class. */
	public string $suffixDataClass = 'Data';

	/** Add extends class in data class. */
	public string $extendsDataClass = ArrayHash::class;

	/** Add final keyword in data class. */
	public bool $finalDataClass = false;

	/** Add class namespace in data class. */
	public string $namespaceDataClass = 'App\Data';
}
