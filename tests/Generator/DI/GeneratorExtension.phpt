<?php

declare(strict_types = 1);

namespace Test\Generator;

use Drago\Generator\Generator;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;
use Tester\FileMock;
use Tests\TestContainer;

$container = require __DIR__ . '/../../bootstrap.php';


class GeneratorExtension extends TestContainer
{
	private function createContainer(): Container
	{
		$container = $this->container;
		$loader = new ContainerLoader($container->getParameters()['tempDir'], true);
		$class = $loader->load(function (Compiler $compiler): void {
			$compiler->loadConfig(FileMock::create('
			generator:
				path: Model/Entity
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
			$compiler->addExtension('generator', new \Drago\Generator\DI\GeneratorExtension('@dibi.connection'));
		});
		return new $class;
	}


	public function test01(): void
	{
		$container = $this->createContainer();
		Assert::type(Generator::class, $container->getByType(Generator::class));
	}
}


$extension = new GeneratorExtension($container);
$extension->run();
