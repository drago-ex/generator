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
	/** The path where the entity will be generated. */
	public string $path = '';

	/** Suffix name. */
	public string $suffix = 'Entity';

	/** Parent entity class. */
	public string $extendsEntity = Entity::class;

	/** Class namespace. */
	public string $namespace = 'App\\Entity';

	/** Allow constant. */
	public bool $constant = true;

	/** Allow return null on the primary key.*/
	public bool $primaryNull = true;

	/** Allow attribute column info. */
	public bool $attributeColumn = true;

	/** Allow convert large characters to lowercase. */
	public bool $lower = false;

	/** Options for form data generator ----------------------------------------------------------------------------- */

	/** Parent form data class. */
	public string $extendsFormData = ExtraArrayHash::class;
}
