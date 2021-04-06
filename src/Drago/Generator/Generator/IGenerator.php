<?php

declare(strict_types=1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;


/**
 * Names of methods for the generator.
 */
interface IGenerator
{
	public function runGeneration(?string $table = null): void;

	public function createPhpFile(string $table): void;
}
