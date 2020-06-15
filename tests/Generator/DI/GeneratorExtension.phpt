<?php

declare(strict_types = 1);

use Drago\Generator\DI\GeneratorExtension;
use Drago\Generator\GeneratorEntity;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;

$container = require __DIR__ . '/../../bootstrap.php';


class TestGeneratorExtension extends TestContainer
{
	private function createContainer(): Container
	{
		$loader = new ContainerLoader($this->container->getParameters()['tempDir'], true);
		$class = $loader->load(function (Compiler $compiler): void {
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
			$compiler->addExtension('generator', new GeneratorExtension());
		});
		return new $class;
	}


	public function test01(): void
	{
		Assert::type(GeneratorEntity::class, $this->createContainer()
			->getByType(GeneratorEntity::class));
	}
}


$extension = new TestGeneratorExtension($container);
$extension->run();
