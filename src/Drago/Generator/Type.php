<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Generator;

use Exception;


/**
 * Data types.
 */
class Type
{
	public const
		TEXT = 'string',
		BINARY = 'resource',
		BOOL = 'bool',
		INTEGER = 'int',
		FLOAT = 'float',
		DATE = '\DateTimeImmutable';


	/**
	 * @throws Exception
	 */
	final public function __construct()
	{
		throw new Exception('Cannot instantiate static class ' . self::class);
	}
}
