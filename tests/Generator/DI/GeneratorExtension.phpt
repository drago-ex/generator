<?php declare(strict_types=1);

/**
 * Test: Drago\Generator\DI\GeneratorExtension
 */

use Contributte\Console\Application;
use Contributte\Console\DI\ConsoleExtension;
use Drago\Generator\DI\GeneratorExtension;
use Drago\Generator\Generator\DataClassGenerator;
use Drago\Generator\Generator\EntityGenerator;
use Drago\Generator\Options;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';


class TestGeneratorExtension extends TestCase
{
	private function createContainer(string $config, bool $consoleMode = false): Container
	{
		$tempDir = __DIR__ . '/temp';
		@mkdir($tempDir); // @ directory may already exist

		$loader = new ContainerLoader($tempDir, true);

		$class = $loader->load(function (Compiler $compiler) use ($config, $consoleMode): void {
			$compiler->loadConfig(Tester\FileMock::create($config . '
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

			$compiler->addExtension('generator', new GeneratorExtension($consoleMode));
			if ($consoleMode) {
				$compiler->addExtension('console', new ConsoleExtension(true));
			}
		}, md5($config) . (int) $consoleMode);

		return new $class;
	}


	public function testConfigurationReachesTheGenerators(): void
	{
		$container = $this->createContainer('
			generator:
				path: entity
				primaryKey: PrimaryKey
				constantPrefix: Column
				final: true
				pathDataClass: data
				constantDataPrefix: Form
		');

		$entity = $container->getByType(EntityGenerator::class)->options;
		Assert::same(
			['entity', 'PrimaryKey', 'Column', true],
			[$entity->path, $entity->primaryKey, $entity->constantPrefix, $entity->final],
		);

		$data = $container->getByType(DataClassGenerator::class)->options;
		Assert::same(['data', 'Form'], [$data->pathDataClass, $data->constantDataPrefix]);
	}


	public function testDefaultsComeFromOptions(): void
	{
		$options = $this->createContainer('')->getByType(EntityGenerator::class)->options;
		Assert::equal(new Options, $options);
	}


	public function testConsoleFindsTheCommandsByTheirAttribute(): void
	{
		$application = $this->createContainer('', consoleMode: true)->getByType(Application::class);
		Assert::true($application->has('app:entity'));
		Assert::true($application->has('app:dataClass'));
	}
}


(new TestGeneratorExtension)->run();
