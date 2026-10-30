<?php declare(strict_types=1);

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
use Nette\Schema\Schema;


/** @property-read Options $config */
final class GeneratorExtension extends CompilerExtension
{
	public function __construct(
		private readonly bool $consoleMode = false,
	) {
	}


	public function getConfigSchema(): Schema
	{
		return Expect::from(new Options);
	}


	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('repository'))
			->setFactory(Repository::class);

		$builder->addDefinition($this->prefix('wordInflector'))
			->setFactory(NoopWordInflector::class);

		$builder->addDefinition($this->prefix('inflector'))
			->setFactory(Inflector::class, [
				'@' . $this->prefix('wordInflector'),
				'@' . $this->prefix('wordInflector'),
			]);

		$builder->addDefinition($this->prefix('entityGenerator'))
			->setFactory(EntityGenerator::class, [
				'@' . $this->prefix('repository'),
				$this->config,
				'@' . $this->prefix('inflector'),
			]);

		$builder->addDefinition($this->prefix('dataClassGenerator'))
			->setFactory(DataClassGenerator::class, [
				'@' . $this->prefix('repository'),
				$this->config,
				'@' . $this->prefix('inflector'),
			]);

		// CLI commands; contributte/console finds them by type and takes the name from #[AsCommand].
		if ($this->consoleMode) {
			$builder->addDefinition($this->prefix('entityCommand'))
				->setFactory(EntityCommand::class);

			$builder->addDefinition($this->prefix('dataClassCommand'))
				->setFactory(DataClassCommand::class);
		}
	}
}
