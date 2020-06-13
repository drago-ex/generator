<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Symfony\Component\Console;


/**
 * Command for generate entity.
 */
class GeneratorCommand extends Console\Command\Command
{
	/** @var string */
	protected static $defaultName = 'make:entity';

	/** @var GeneratorEntity */
	private $generatorEntity;


	public function __construct(GeneratorEntity $generatorEntity)
	{
		parent::__construct();
		$this->generatorEntity = $generatorEntity;
	}


	/**
	 * Configures the current command.
	 */
	protected function configure(): void
	{
		$this->setName(self::$defaultName)
			->setDescription('Generating entity from database.')
			->addArgument('table', Console\Input\InputArgument::OPTIONAL);
	}


	/**
	 * Executes the current command.
	 * @return int|void
	 * @throws \Dibi\Exception
	 * @throws \Throwable
	 */
	protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
	{
		$this->generatorEntity->runGeneration($input->getArgument('table'));
	}
}
