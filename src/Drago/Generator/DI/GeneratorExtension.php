<?php

declare(strict_types=1);

use Drago\Generator\DI\GeneratorExtension;
use Drago\Generator\Generator\EntityGenerator;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;
use Tester\FileMock;
use Tester\TestCase;

require __DIR__ . '/../../vendor/autoload.php';


class TestGeneratorExtension extends TestCase
{
	public function createContainer(): Container
	{
		$tempDir = __DIR__ . '/tmp';

		if (!is_dir($tempDir)) {
			mkdir($tempDir, 0777, true);
		}

		$loader = new ContainerLoader($tempDir, true);

		$class = $loader->load(function (Compiler $compiler): void {

			$compiler->loadConfig(FileMock::create('
generator:
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

			$compiler->addExtension(
				'generator',
				new GeneratorExtension
			);
		});

		return new $class;
	}


	public function test01(): void
	{
		$container = $this->createContainer();

		Assert::type(
			EntityGenerator::class,
			$container->getByType(EntityGenerator::class)
		);
	}
}


(new TestGeneratorExtension())->run();
