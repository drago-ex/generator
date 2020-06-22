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

	/** Allow attribute column info. */
	public bool $attributeColumn = true;


	/** Options for form data generator ----------------------------------------------------------------------------- */

	/** The path where the classes will be generated. */
	public string $pathFormData = '';

	/** Allow constant. */
	public bool $constantFormData = true;

	/** Add suffix name. */
	public string $suffixFormData = 'Data';

	/** Add extends class. */
	public string $extendsFormData = ExtraArrayHash::class;

	/** Allow extends class */
	public bool $extendFormDataOn = true;

	/** Add final keyword */
	public bool $finalFormData = true;

	/** Add class namespace. */
	public string $namespaceFormData = 'App\\Data';
}
