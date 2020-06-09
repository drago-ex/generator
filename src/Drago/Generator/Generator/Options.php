<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;


/**
 * Options for generate entity.
 */
class Options
{
	/** @var string  the path where the entity will be generated */
	public $path;

	/** @var string  suffix name */
	public $suffix = 'Entity';

	/** @var string  parent */
	public $extends = \Drago\Database\Entity::class;

	/** @var string  namespace */
	public $namespace = 'App\\Entity';

	/** @var bool  allow property */
	public $property = true;

	/** @var string  visibility property */
	public $propertyVisibility = 'public';

	/** @var bool  allow constant */
	public $constant = true;

	/** @var bool  allow attributes */
	public $attribute = true;

	/** @var bool  allow attributes column length */
	public $attributeLength = false;

	/** @var bool  allow the getter method */
	public $getter = true;

	/** @var bool  allow return null on the primary key */
	public $getterPrimaryNull = true;

	/** @var bool  allow the setter method */
	public $setter = true;

	/** @var bool  allow convert large characters to lowercase */
	public $lower = false;
}
