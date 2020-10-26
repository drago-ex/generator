<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Drago\Database\Entity;
use Drago\Utils\ExtraArrayHash;


/**
 * Options for generate php file.
 */
class Options
{
	/** Base options ------------------------------------------------------------------------------------------------ */

	/** Allow convert uppercase characters to lowercase (typical of Oracle). */
	public bool $lower = false;


	/** Options for entity generator -------------------------------------------------------------------------------- */

	/** The path where the classes will be generated. */
	public string $path = '';

	/** Allow constant. */
	public bool $constant = true;

	/** Allow constant column length. */
	public bool $constantLength = true;

	/** Allow table references in the property */
	public bool $references = false;

	/** Add suffix name. */
	public string $suffix = 'Entity';

	/** Add extends class. */
	public string $extends = Entity::class;

	/** Allow extends class */
	public bool $extendsOn = true;

	/** Add final keyword */
	public bool $final = true;

	/** Add class namespace. */
	public string $namespace = 'App\\Entity';


	/** Options for data class generator ----------------------------------------------------------------------------- */

	/** The path where the classes will be generated. */
	public string $pathDataClass = '';

	/** Allow constant. */
	public bool $constantDataClass = true;

	/** Allow constant column length. */
	public bool $constantLengthDataClass = true;

	/** Allow table references in the property */
	public bool $referencesDataClass = false;

	/** Add suffix name. */
	public string $suffixDataClass = 'Data';

	/** Add extends class. */
	public string $extendsDataClass = ExtraArrayHash::class;

	/** Allow extends class */
	public bool $extendsOnDataClass = true;

	/** Add final keyword */
	public bool $finalDataClass = true;

	/** Add class namespace. */
	public string $namespaceDataClass = 'App\\Data';
}
