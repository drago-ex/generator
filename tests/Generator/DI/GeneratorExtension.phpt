<?php

/**
 * Test: Drago\Generator\DI\GeneratorExtension
 */

declare(strict_types=1);

use Drago\Generator\DI\GeneratorExtension;
use Drago\Generator\Generator\EntityGenerator;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;
use Tester\TestCase;

$container = require __DIR__ . '/../../bootstrap.php';


class TestGeneratorExtension extends TestCase
{
	protected Container $container;


	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	public function createContainer(): Container
	{
		$loader = new ContainerLoader($this->container->getParameters()['tempDir'], true);
		$class = $loader->load(function (Compiler $compiler): void {
			$compiler->loadConfig(Tester\FileMock::create('
			generator:
				# generator entity
				path: entity
				tableName: tableName
				primaryKey: primaryKeyName
				columnInfo: true
				constant: true
				constantSize: true
				constantPrefix: Column
				references: true
				suffix: Entity
				extendsOn: true
				extends: Drago\Database\Entity
				final: true
				namespace: App\Entity

				# generator data class
				pathDataClass: data
				constantDataClass: true
				constantSizeDataClass: true
				constantDataPrefix: Form
				referencesDataClass: true
				suffixDataClass: Data
				extendsDataClass: Drago\Utils\ExtraArrayHash
				finalDataClass: false
				namespaceDataClass: App\Data

			services:
				dibi.connection:
					factory: Dibi\Connection([
						driver: mysqli
						host: 127.0.0.1
						username: root
						password: root
						database: test
						lazy: true
					])
			', 'neon'));
			$compiler->addExtension('generator', new GeneratorExtension);
		});
		return new $class;
	}


	public function test01(): void
	{
		Assert::type(EntityGenerator::class, $this->createContainer()
			->getByType(EntityGenerator::class));
	}
}

(new TestGeneratorExtension($container))->run();
