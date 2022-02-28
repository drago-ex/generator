<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Generator\DI;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\NoopWordInflector;
use Drago\Generator\Command\DataClassCommand;
use Drago\Generator\Command\EntityCommand;
use Drago\Generator\Generator\DataClassGenerator;
use Drago\Generator\Generator\EntityGenerator;
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

		$schema = new Processor;
		$normalized = $schema->process(Expect::from(new Options), $this->config);
		$builder->addDefinition($this->prefix('generator'))
			->setFactory(EntityGenerator::class)
			->setArguments(['@generator.repository', $normalized, '@generator.inflector']);

		$builder->addDefinition($this->prefix('generatorDataClass'))
			->setFactory(DataClassGenerator::class)
			->setArguments(['@generator.repository', $normalized, '@generator.inflector']);

		$builder->addDefinition($this->prefix('command'))
			->setFactory(EntityCommand::class);

		$builder->addDefinition($this->prefix('dataClassCommand'))
			->setFactory(DataClassCommand::class);
	}
}
