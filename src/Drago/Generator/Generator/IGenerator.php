<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Generator\Generator;


/**
 * Interface defining method names for the generator.
 */
interface IGenerator
{
	/**
	 * Executes the generation process for a given table or all tables.
	 *
	 * @param string|null $table The name of the table to generate the file for, or null to process all tables.
	 */
	public function runGeneration(?string $table = null): void;

	/**
	 * Creates a PHP file for the specified table.
	 *
	 * @param string $table The name of the table to generate the file for.
	 */
	public function createPhpFile(string $table): void;
}
