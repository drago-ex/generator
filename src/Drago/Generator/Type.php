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
	public const string Date = '\DateTimeImmutable';
	public const string Float = 'float';
	public const string Integer = 'int';
	public const string Bool = 'bool';
	public const string Binary = 'resource';
	public const string Text = 'string';
}
