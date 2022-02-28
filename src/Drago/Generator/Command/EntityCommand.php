<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Generator\Command;

use Drago\Generator\Generator\EntityGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;


/**
 * Command for generate entity.
 */
class EntityCommand extends Command
{
	/**
	 * The name of the command.
	 */
	protected static $defaultName = 'make:entity';


	public function __construct(
		private EntityGenerator $generatorEntity
	) {
		parent::__construct();
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
	 * @throws Throwable
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$this->generatorEntity->runGeneration($input->getArgument('table'));
		$output->writeln('Generation was successful.');

		return Command::SUCCESS;
	}
}
