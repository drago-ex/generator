<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator\DI;

use Drago\Generator\Generator;
use Drago\Generator\GeneratorCommand;
use Drago\Generator\Options;
use Drago\Generator\Repository;
use Nette;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;


/**
 * Register services for generator.
 */
class GeneratorExtension extends CompilerExtension
{
	private $service;


	public function __construct($service = null)
	{
		$this->service = $service;
	}


	public function loadConfiguration(): void
	{
		$schema = new Nette\Schema\Processor;
		$normalized = $schema->process(Expect::from(new Options), $this->config);

		$builder = $this->getContainerBuilder();
		$repository = $builder
			->addDefinition($this->prefix('repository'))
			->setFactory(Repository::class);

		if ($this->service) {
			$repository->setArguments([$this->service]);
		}

		$builder->addDefinition($this->prefix('generator'))
			->setFactory(Generator::class)
			->setArguments(['@generator.repository', $normalized]);

		$builder->addDefinition($this->prefix('command'))
			->setFactory(GeneratorCommand::class);
	}
}
