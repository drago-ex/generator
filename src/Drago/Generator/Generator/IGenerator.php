<?php

declare(strict_types=1);

namespace Drago\Generator\Generator;


/** Interface defining method names for the generator. */
interface IGenerator
{
	/** Executes the generation process for a given table or all tables. */
	public function runGeneration(?string $table = null): void;

	/** Creates a PHP file for the specified table. */
	public function createPhpFile(string $table): void;
}
