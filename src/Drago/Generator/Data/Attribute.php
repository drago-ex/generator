<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator\Data;


/**
 * Column attribute.
 */
class Attribute
{
	public const
		AUTO_INCREMENT = 'autoIncrement',
		SIZE = 'length',
		DEFAULT = 'default',
		NULLABLE = 'nullable',
		TYPE = 'type';


	final public function __construct()
	{
		throw new \Exception('Cannot instantiate static class ' . __CLASS__);
	}
}
