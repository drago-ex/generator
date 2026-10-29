<?php declare(strict_types=1);

namespace Drago\Generator\Generator;


interface IGenerator
{
	function runGeneration(?string $table = null): void;

	function createPhpFile(string $table): void;
}
