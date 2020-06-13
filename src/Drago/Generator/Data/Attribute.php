<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator\Data;

use Exception;


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


	/**
	 * @throws Exception
	 */
	final public function __construct()
	{
		throw new Exception('Cannot instantiate static class ' . __CLASS__);
	}
}
