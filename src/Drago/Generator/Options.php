<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Generator;

use Drago\Database\Entity;
use Drago\Utils\ExtraArrayHash;


/**
 * Options for generate php file.
 */
class Options
{
	/** Base options ------------------------------------------------------------------------------------------------ */

	/** Allow convert uppercase to lowercase keys in array (typical of Oracle). */
	public bool $lower = false;

	/** Options for entity generator -------------------------------------------------------------------------------- */

	/** The path where the classes will be generated. */
	public string $path = '';

	/** Custom name for the table in constant. */
	public string|null $tableName = null;

	/** Custom name for the primary key in the table at the constant. */
	public string|null $primaryKey = null;

	/** Allow constant. */
	public bool $constant = true;

	/** Allow basic column info */
	public bool $columnInfo = false;

	/** Add the name before the constant. */
	public string|null $constantPrefix = null;

	/** Allow constant column size. */
	public bool $constantSize = false;

	/** Allow table references in the property */
	public bool $references = false;

	/** Add suffix name. */
	public string $suffix = 'Entity';

	/** Allow extends class */
	public bool $extendsOn = true;

	/** Add extends class. */
	public string $extends = Entity::class;

	/** Add final keyword */
	public bool $final = false;

	/** Add class namespace. */
	public string $namespace = 'App\\Entity';

	/** Options for data class generator ----------------------------------------------------------------------------- */

	/** The path where the classes will be generated. */
	public string $pathDataClass = '';

	/** Allow constant. */
	public bool $constantDataClass = true;

	/** Allow constant column size. */
	public bool $constantSizeDataClass = true;

	/** Add the name before the constant. */
	public string|null $constantDataPrefix = null;

	/** Allow table references in the property */
	public bool $referencesDataClass = false;

	/** Add suffix name. */
	public string $suffixDataClass = 'Data';

	/** Add extends class. */
	public string $extendsDataClass = ExtraArrayHash::class;

	/** Add final keyword */
	public bool $finalDataClass = false;

	/** Add class namespace. */
	public string $namespaceDataClass = 'App\\Data';
}
