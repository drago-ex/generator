<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;


/**
 * Command for generate entity.
 */
class GeneratorCommand extends Command
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
			->addArgument('table', InputArgument::OPTIONAL);
	}


	/**
	 * Executes the current command.
	 * @return int|void
	 * @throws Exception
	 * @throws Throwable
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->generatorEntity->runGeneration($input->getArgument('table'));
	}
}
