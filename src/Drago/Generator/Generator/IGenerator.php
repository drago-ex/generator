<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Generator\Generator;


/**
 * Names of methods for the generator.
 */
interface IGenerator
{
	public function runGeneration(null|string $table = null): void;

	public function createPhpFile(string $table): void;
}
