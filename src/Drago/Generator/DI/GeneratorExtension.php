<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator\DI;

use Drago\Generator;
use Nette\DI;
use Nette\Schema;


/**
 * Register services for generator.
 */
class GeneratorExtension extends DI\CompilerExtension
{
	private $service;


	public function __construct($service = null)
	{
		$this->service = $service;
	}


	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$repository = $builder
			->addDefinition($this->prefix('repository'))
			->setFactory(Generator\Repository::class);

		if ($this->service) {
			$repository->setArguments([$this->service]);
		}

		$schema = new Schema\Processor();
		$normalized = $schema->process(Schema\Expect::from(new Generator\Options()), $this->config);
		$builder->addDefinition($this->prefix('generator'))
			->setFactory(Generator\Generator::class)
			->setArguments(['@generator.repository', $normalized]);

		$builder->addDefinition($this->prefix('command'))
			->setFactory(Generator\GeneratorCommand::class);
	}
}
