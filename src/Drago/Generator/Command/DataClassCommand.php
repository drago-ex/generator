<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator\Command;

use Drago\Generator\DataClassGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;


/**
 * Command for generate data class.
 */
class DataClassCommand extends Command
{
	protected static $defaultName = 'make:dataClass';
	private DataClassGenerator $dataClassGenerator;


	public function __construct(DataClassGenerator $dataClassGenerator)
	{
		parent::__construct();
		$this->dataClassGenerator = $dataClassGenerator;
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
	 * @throws Throwable
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->dataClassGenerator->runGeneration($input->getArgument('table'));
		$output->writeln('Generation was successful.');
	}
}
