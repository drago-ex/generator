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

	/** Allow constant. */
	public bool $constant = true;

	/** Allow return null on the primary key.*/
	public bool $primaryNull = true;

	/** Allow convert large characters to lowercase (typical of Oracle). */
	public bool $lower = false;


	/** Options for entity generator -------------------------------------------------------------------------------- */

	/** The path where the entity will be generated. */
	public string $path = '';

	/** Suffix name. */
	public string $suffix = 'Entity';

	/** Parent entity class. */
	public string $extends = Entity::class;

	/** Class namespace. */
	public string $namespace = 'App\\Entity';

	/** Allow attribute column info. */
	public bool $attributeColumn = true;


	/** Options for form data generator ----------------------------------------------------------------------------- */

	public string $pathFormData = '';

	/** Suffix name. */
	public string $suffixFormData = 'Data';

	/** Parent entity class. */
	public string $extendsFormData = ExtraArrayHash::class;

	/** Class namespace. */
	public string $namespaceFormData = 'App\\Data';
}
