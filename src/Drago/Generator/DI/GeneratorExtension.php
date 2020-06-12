<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator\DI;

use Doctrine\Inflector;
use Drago\Generator;
use Nette\DI;
use Nette\Schema;


/**
 * Register services for generator.
 */
class GeneratorExtension extends DI\CompilerExtension
{
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('repository'))
			->setFactory(Generator\Repository::class);

		$builder->addDefinition($this->prefix('noopWordInflector'))
			->setFactory(Inflector\NoopWordInflector::class);

		$builder->addDefinition($this->prefix('inflector'))
			->setFactory(Inflector\Inflector::class)
			->setArguments(['@generator.noopWordInflector', '@generator.noopWordInflector']);

		$builder->addDefinition($this->prefix('helpers'))
			->setFactory(Generator\Helpers::class);

		$schema = new Schema\Processor;
		$normalized = $schema->process(Schema\Expect::from(new Generator\Options), $this->config);
		$builder->addDefinition($this->prefix('generatorEntity'))
			->setFactory(Generator\GeneratorEntity::class)
			->setArguments(['@generator.repository', $normalized, '@generator.inflector', '@generator.helpers']);

		$builder->addDefinition($this->prefix('command'))
			->setFactory(Generator\GeneratorCommand::class);
	}
}
