<?php

declare(strict_types = 1);

namespace Test\Generator;

use Drago\Generator;
use Nette\DI;
use Tester;
use Tests;

$container = require __DIR__ . '/../../bootstrap.php';


class GeneratorExtension extends Tests\TestContainer
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
			$compiler->addExtension('generator', new Generator\DI\GeneratorExtension('@dibi.connection'));
		});
		return new $class;
	}


	public function test01(): void
	{
		$container = $this->createContainer();
		Tester\Assert::type(Generator\Generator::class, $container->getByType(Generator\Generator::class));
	}
}


$extension = new GeneratorExtension($container);
$extension->run();
