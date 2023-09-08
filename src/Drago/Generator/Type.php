<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Generator;


/**
 * Data types.
 */
final class Type
{
	public const
		text = 'string',
		Binary = 'resource',
		Bool = 'bool',
		Integer = 'int',
		Float = 'float',
		Date = '\DateTimeImmutable';
}
