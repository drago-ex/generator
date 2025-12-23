<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Generator\Command;

use Drago\Generator\Generator\DataClassGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;


/**
 * Command for generating a data class from a database table.
 */
#[AsCommand(name: 'app:dataClass')]
class DataClassCommand extends Command
{
	/**
	 * Constructor for DataClassCommand.
	 * @param DataClassGenerator $dataClassGenerator
	 */
	public function __construct(
		private readonly DataClassGenerator $dataClassGenerator,
	) {
		parent::__construct();
	}


	/**
	 * Configures the current command.
	 */
	protected function configure(): void
	{
		$this->setDescription('Generates entity classes from a database table.')
			->addArgument('table', InputArgument::OPTIONAL, 'The name of the database table');
	}


	/**
	 * Executes the command.
	 * @throws Throwable
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		try {
			$this->dataClassGenerator->runGeneration($input->getArgument('table'));
			$output->writeln('✅ Generation was successful.');
		} catch (Throwable $exception) {
			$output->writeln('<error>Error: ' . $exception->getMessage() . '</error>');
			return Command::FAILURE;
		}

		return Command::SUCCESS;
	}
}
