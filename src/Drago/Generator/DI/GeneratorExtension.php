<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator\DI;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\NoopWordInflector;
use Drago\Generator;
use Drago\Generator\Helpers;
use Nette;
use Nette\Schema;


/**
 * Register services for generator.
 */
class GeneratorExtension extends Nette\DI\CompilerExtension
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

		$builder->addDefinition($this->prefix('noopWordInflector'))
			->setFactory(NoopWordInflector::class);

		$builder->addDefinition($this->prefix('inflector'))
			->setFactory(Inflector::class)
			->setArguments(['@generator.noopWordInflector', '@generator.noopWordInflector']);

		$builder->addDefinition($this->prefix('helpers'))
			->setFactory(Helpers::class);

		$schema = new Schema\Processor;
		$normalized = $schema->process(Schema\Expect::from(new Generator\Options), $this->config);
		$builder->addDefinition($this->prefix('generator'))
			->setFactory(Generator\Generator::class)
			->setArguments(['@generator.repository', $normalized, '@generator.inflector', '@generator.helpers']);

		$builder->addDefinition($this->prefix('command'))
			->setFactory(Generator\GeneratorCommand::class);
	}
}
