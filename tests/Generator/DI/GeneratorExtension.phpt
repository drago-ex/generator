<?php

declare(strict_types = 1);

use Drago\Generator;
use Nette\DI;

$container = require __DIR__ . '/../../bootstrap.php';


class GeneratorExtension extends TestContainer
{
	private function createContainer(): DI\Container
	{
		$loader = new DI\ContainerLoader($this->container->getParameters()['tempDir'], true);
		$class = $loader->load(function (DI\Compiler $compiler): void {
			$compiler->loadConfig(Tester\FileMock::create('
			generator:
				path: entity
			services:
				dibi.connection:
					factory: Dibi\Connection([
						driver: mysqli
						host: 127.0.0.1
						username: root
						password:
						database: test
						lazy: true
					])
			', 'neon'));
			$compiler->addExtension('generatorEntity', new Drago\Generator\DI\GeneratorExtension);
		});
		return new $class;
	}


	public function test01(): void
	{
		Tester\Assert::type(Generator\GeneratorEntity::class, $this->createContainer()
			->getByType(Generator\GeneratorEntity::class));
	}
}


$extension = new GeneratorExtension($container);
$extension->run();
