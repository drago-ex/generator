<?php

declare(strict_types = 1);

namespace Test\Generator;
use Drago\Generator\Generator;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Test\TestCaseAbstract;
use Tester\Assert;
use Tester\FileMock;

$container = require __DIR__ . '/../../bootstrap.php';


class GeneratorExtension extends TestCaseAbstract
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
						host: localhost
						username: root
						password:
						database: test
					])
			', 'neon'));
			$compiler->addExtension('generator', new \Drago\Generator\DI\GeneratorExtension);
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
