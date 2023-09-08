<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Generator;

use Exception;


/**
 * Column attributes.
 */
final class Attribute
{
	public const
		AutoIncrement = 'autoIncrement',
		Length = 'length',
		Default = 'default',
		Nullable = 'nullable',
		Type = 'type';
}
