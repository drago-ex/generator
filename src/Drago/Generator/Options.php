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
 * Options for generating PHP files.
 */
class Options
{
	/** Base options ------------------------------------------------------------------------------------------------ */

	/**
	 * Allow converting uppercase to lowercase keys in arrays (typical of Oracle).
	 * @var bool
	 */
	public bool $lower = false;

	/** Options for entity generator -------------------------------------------------------------------------------- */

	/**
	 * The path where the classes will be generated.
	 * @var string
	 */
	public string $path = '';

	/**
	 * Custom name for the table in constant.
	 * @var string|null
	 */
	public ?string $tableName = null;

	/**
	 * Custom name for the primary key in the table at the constant.
	 * @var string|null
	 */
	public ?string $primaryKey = null;

	/**
	 * Allow constant.
	 * @var bool
	 */
	public bool $constant = true;

	/**
	 * Allow basic column info.
	 * @var bool
	 */
	public bool $columnInfo = false;

	/**
	 * Add the name before the constant.
	 * @var string|null
	 */
	public ?string $constantPrefix = null;

	/**
	 * Allow constant column size.
	 * @var bool
	 */
	public bool $constantSize = false;

	/**
	 * Allow table references in the property.
	 * @var bool
	 */
	public bool $references = false;

	/**
	 * Add suffix name.
	 * @var string
	 */
	public string $suffix = 'Entity';

	/**
	 * Allow extending a class.
	 * @var bool
	 */
	public bool $extendsOn = true;

	/**
	 * Add extends class.
	 * @var string
	 */
	public string $extends = Entity::class;

	/**
	 * Add final keyword.
	 * @var bool
	 */
	public bool $final = false;

	/**
	 * Add class namespace.
	 * @var string
	 */
	public string $namespace = 'App\Entity';

	/** Options for data class generator ----------------------------------------------------------------------------- */

	/**
	 * The path where the classes will be generated.
	 * @var string
	 */
	public string $pathDataClass = '';

	/**
	 * Allow constant in data class.
	 * @var bool
	 */
	public bool $constantDataClass = true;

	/**
	 * Allow constant column size in data class.
	 * @var bool
	 */
	public bool $constantSizeDataClass = true;

	/**
	 * Add the name before the constant in data class.
	 * @var string|null
	 */
	public ?string $constantDataPrefix = null;

	/**
	 * Allow table references in the property in data class.
	 * @var bool
	 */
	public bool $referencesDataClass = false;

	/**
	 * Add suffix name in data class.
	 * @var string
	 */
	public string $suffixDataClass = 'Data';

	/**
	 * Add extends class in data class.
	 * @var string
	 */
	public string $extendsDataClass = ExtraArrayHash::class;

	/**
	 * Add final keyword in data class.
	 * @var bool
	 */
	public bool $finalDataClass = false;

	/**
	 * Add class namespace in data class.
	 * @var string
	 */
	public string $namespaceDataClass = 'App\Data';
}
