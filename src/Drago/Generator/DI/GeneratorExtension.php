<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Generator\DI;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\NoopWordInflector;
use Drago\Database\Entity;
use Drago\Generator\Command\DataClassCommand;
use Drago\Generator\Command\EntityCommand;
use Drago\Generator\Generator\DataClassGenerator;
use Drago\Generator\Generator\EntityGenerator;
use Drago\Generator\Options;
use Drago\Generator\Repository;
use Drago\Utils\ExtraArrayHash;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;


/**
 * Register services for generator.
 */
class GeneratorExtension extends CompilerExtension
{
	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'lower' => Expect::bool(false),
			'path' => Expect::string(''),
			'tableName' => Expect::string(),
			'primaryKey' => Expect::string(),
			'columnInfo' => Expect::bool(false),
			'constant' => Expect::bool(true),
			'constantLength' => Expect::bool(false),
			'references' => Expect::bool(false),
			'suffix' => Expect::string('Entity'),
			'extendsOn' => Expect::bool(true),
			'extends' => Expect::string(Entity::class),
			'final' => Expect::bool(false),
			'namespace' => Expect::string('App\\Entity'),
			'pathDataClass' => Expect::string(''),
			'constantDataClass' => Expect::bool(true),
			'constantLengthDataClass' => Expect::bool(true),
			'referencesDataClass' => Expect::bool(false),
			'suffixDataClass' => Expect::string('Data'),
			'extendsDataClass' => Expect::string(ExtraArrayHash::class),
			'finalDataClass' => Expect::bool(false),
			'namespaceDataClass' => Expect::string('App\\Data'),
		]);
	}


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
