<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator;

use Symfony\Component\Console\Command;
use Symfony\Component\Console\Input;
use Symfony\Component\Console\Output;


/**
 * Command for generate entity.
 */
class GeneratorCommand extends Command\Command
{
	/** @var Generator */
	private $generator;


	public function __construct(Generator $generator)
	{
		parent::__construct();
		$this->generator = $generator;
	}


	/**
	 * Configures the current command.
	 */
	protected function configure(): void
	{
		$this->setName('generate:entity')
			->setDescription('Generating entity from database.')
			->addArgument('table', Input\InputArgument::OPTIONAL);
	}


	/**
	 * Executes the current command.
	 * @throws \Dibi\Exception
	 * @throws \Throwable
	 */
	protected function execute(Input\InputInterface $input, Output\OutputInterface $output): void
	{
		$this->generator->runGenerate($input->getArgument('table'));
	}
}
