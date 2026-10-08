<?php

declare(strict_types=1);

namespace Drago\Generator\Generator;


interface IGenerator
{
	public function runGeneration(?string $table = null): void;

	public function createPhpFile(string $table): void;
}
