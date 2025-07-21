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
 * Compiler extension to register services for generator.
 */
class GeneratorExtension extends CompilerExtension
{
	/**
	 * Defines the configuration schema.
	 * @return Schema
	 */
	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'lower' => Expect::bool(false),
			'path' => Expect::string(''),
			'tableName' => Expect::string()->nullable(),
			'primaryKey' => Expect::string()->nullable(),
			'columnInfo' => Expect::bool(false),
			'constant' => Expect::bool(true),
			'constantSize' => Expect::bool(false),
			'constantPrefix' => Expect::string()->nullable(),
			'references' => Expect::bool(false),
			'suffix' => Expect::string('Entity'),
			'extendsOn' => Expect::bool(true),
			'extends' => Expect::string(Entity::class),
			'final' => Expect::bool(false),
			'namespace' => Expect::string('App\Entity'),
			'pathDataClass' => Expect::string(''),
			'constantDataClass' => Expect::bool(true),
			'constantDataPrefix' => Expect::string()->nullable(),
			'constantSizeDataClass' => Expect::bool(true),
			'referencesDataClass' => Expect::bool(false),
			'suffixDataClass' => Expect::string('Data'),
			'extendsDataClass' => Expect::string(ExtraArrayHash::class),
			'finalDataClass' => Expect::bool(false),
			'namespaceDataClass' => Expect::string('App\Data'),
		]);
	}


	/**
	 * Loads configuration and registers services.
	 * @throws \Exception
	 */
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		// Register repository service
		$builder->addDefinition($this->prefix('repository'))
			->setFactory(Repository::class);

		// Register word inflector service
		$builder->addDefinition($this->prefix('wordInflector'))
			->setFactory(NoopWordInflector::class);

		// Register inflector service with injected dependencies
		$builder->addDefinition($this->prefix('inflector'))
			->setFactory(Inflector::class)
			->setArguments(['@generator.wordInflector', '@generator.wordInflector']);

		// Process configuration options
		$schemaProcessor = new Processor;
		$normalizedConfig = $schemaProcessor->process(Expect::from(new Options), $this->config);

		// Register entity generator service
		$builder->addDefinition($this->prefix('generator'))
			->setFactory(EntityGenerator::class)
			->setArguments(['@generator.repository', $normalizedConfig, '@generator.inflector']);

		// Register data class generator service
		$builder->addDefinition($this->prefix('generatorDataClass'))
			->setFactory(DataClassGenerator::class)
			->setArguments(['@generator.repository', $normalizedConfig, '@generator.inflector']);

		// Register entity command
		$builder->addDefinition($this->prefix('command'))
			->setFactory(EntityCommand::class);

		// Register data class command
		$builder->addDefinition($this->prefix('dataClassCommand'))
			->setFactory(DataClassCommand::class);
	}
}
