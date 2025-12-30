<?php

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
use Nette\DI\Definitions\ServiceDefinition;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use Symfony\Component\Console\Command\Command;


final class GeneratorExtension extends CompilerExtension
{
	private bool $consoleMode;


	public function __construct(bool $consoleMode = false)
	{
		$this->consoleMode = $consoleMode;
	}


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

			// Data class
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


	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		// Repository
		$builder->addDefinition($this->prefix('repository'))
			->setFactory(Repository::class);

		// Word inflector
		$builder->addDefinition($this->prefix('wordInflector'))
			->setFactory(NoopWordInflector::class);

		// Inflector
		$builder->addDefinition($this->prefix('inflector'))
			->setFactory(Inflector::class, [
				'@' . $this->prefix('wordInflector'),
				'@' . $this->prefix('wordInflector'),
			]);

		// Normalize config
		$processor = new Processor();
		$options = $processor->process(
			Expect::from(new Options),
			$this->config
		);

		// Entity generator
		$builder->addDefinition($this->prefix('entityGenerator'))
			->setFactory(EntityGenerator::class, [
				'@' . $this->prefix('repository'),
				$options,
				'@' . $this->prefix('inflector'),
			]);

		// Data class generator
		$builder->addDefinition($this->prefix('dataClassGenerator'))
			->setFactory(DataClassGenerator::class, [
				'@' . $this->prefix('repository'),
				$options,
				'@' . $this->prefix('inflector'),
			]);

		// CLI commands
		if ($this->consoleMode) {
			$builder->addDefinition($this->prefix('entityCommand'))
				->setFactory(EntityCommand::class);

			$builder->addDefinition($this->prefix('dataClassCommand'))
				->setFactory(DataClassCommand::class);
		}
	}


	public function beforeCompile(): void
	{
		if (!$this->consoleMode) {
			return;
		}

		$builder = $this->getContainerBuilder();
		$commands = $builder->findByType(Command::class);

		foreach ($commands as $serviceName => $serviceDef) {
			assert($serviceDef instanceof ServiceDefinition);
			$serviceDef->addTag('console.command');
		}
	}
}
