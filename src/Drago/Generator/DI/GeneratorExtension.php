<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator\DI;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\NoopWordInflector;
use Drago\Generator\GeneratorCommand;
use Drago\Generator\GeneratorEntity;
use Drago\Generator\Helpers;
use Drago\Generator\Options;
use Drago\Generator\Repository;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Processor;


/**
 * Register services for generator.
 */
class GeneratorExtension extends CompilerExtension
{
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('repository'))
			->setFactory(Repository::class);

		$builder->addDefinition($this->prefix('wordInflector'))
			->setFactory(NoopWordInflector::class);

		$builder->addDefinition($this->prefix('inflector'))
			->setFactory(Inflector::class)
			->setArguments(['@generator.wordInflector', '@generator.wordInflector']);

		$builder->addDefinition($this->prefix('helpers'))
			->setFactory(Helpers::class);

		$schema = new Processor;
		$normalized = $schema->process(Expect::from(new Options), $this->config);
		$builder->addDefinition($this->prefix('generator'))
			->setFactory(GeneratorEntity::class)
			->setArguments(['@generator.repository', $normalized, '@generator.inflector', '@generator.helpers']);

		$builder->addDefinition($this->prefix('command'))
			->setFactory(GeneratorCommand::class);
	}
}
