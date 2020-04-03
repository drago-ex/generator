<?php

declare(strict_types = 1);

use Drago\Generator\Generator;
use Nette\DI;

$container = require __DIR__ . '/../../bootstrap.php';


class GeneratorExtension extends TestContainer
{
	private function createContainer(): DI\Container
	{
		$container = $this->container;
		$loader = new DI\ContainerLoader($container->getParameters()['tempDir'], true);
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
			$compiler->addExtension('generator', new Drago\Generator\DI\GeneratorExtension('@dibi.connection'));
		});
		return new $class;
	}


	public function test01(): void
	{
		$container = $this->createContainer();
		Tester\Assert::type(Generator::class, $container->getByType(Generator::class));
	}
}


$extension = new GeneratorExtension($container);
$extension->run();
