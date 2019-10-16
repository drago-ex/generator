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
	/** @var  string  the path where the entity will be generated */
	public $path;

	/** @var  string  entity suffix name */
	public $suffix = 'Entity';

	/** @var  string  parent for entity */
	public $extends = \Drago\Database\Entity::class;

	/** @var  string  namespace for entity */
	public $namespace = 'App\\Model\\Entity';

	/** @var  bool  add property */
	public $property = true;

	/** @var  bool  add constant to the entity */
	public $constant = true;

	/** @var  bool  add attributes to the entity */
	public $attribute = true;

	/** @var  bool  add the getter method */
	public $getter = true;

	/** @var  bool  add the setter method */
	public $setter = true;

	/** @var  bool  enable capitalization of table name  */
	public $upper = false;
}
