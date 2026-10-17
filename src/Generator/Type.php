<?php

declare(strict_types=1);

namespace Drago\Generator;


final class Type
{
	public const string Date = '\DateTimeImmutable';
	public const string Float = 'float';
	public const string Integer = 'int';
	public const string Bool = 'bool';
	/** Dibi returns binary columns as strings; `resource` is not a valid property type. */
	public const string Binary = 'string';
	public const string Text = 'string';
}
