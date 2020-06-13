<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Dibi\Exception;
use Symfony\Component\Console\Command;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;
use Throwable;


/**
 * Command for generate entity.
 */
class GeneratorCommand extends Command\Command
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
			->addArgument('table', Input\InputArgument::OPTIONAL);
	}


	/**
	 * Executes the current command.
	 * @return int|void
	 * @throws Exception
	 * @throws Throwable
	 */
	protected function execute(Input\InputInterface $input, Output\OutputInterface $output)
	{
		$this->generatorEntity->runGeneration($input->getArgument('table'));
	}
}
