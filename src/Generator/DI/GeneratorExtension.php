<?php

declare(strict_types = 1);

/**
 * Drago Generator
 * Package built on Nette Framework
 */
namespace Drago\Generator\DI;

use Drago\Generator\Generator;
use Drago\Generator\GeneratorCommand;
use Drago\Generator\Options;
use Drago\Generator\Repository;
use Nette\DI\CompilerExtension;
use Nette\Schema\Processor;
use Nette\Schema\Expect;

/**
 * Register services for generator.
 * @package Drago\Generator
 */
class GeneratorExtension extends CompilerExtension
{

	/** @var string */
	private $service;


	public function __construct($service = null)
	{
		$this->service = $service;
	}


	public function loadConfiguration(): void
	{
		$options = new Options();
		$processor  = new Processor();
		$normalized = $processor->process(Expect::from($options), $this->config);

		$builder = $this->getContainerBuilder();
		$builder
			->addDefinition($this->prefix('Generator'))
			->setFactory(Generator::class, ['options' => $normalized]);

		$builder
			->addDefinition($this->prefix('Repository'))
			->setFactory(Repository::class, [$this->service]);

		$builder
			->addDefinition($this->prefix('GeneratorCommand'))
			->setFactory(GeneratorCommand::class);
	}
}
