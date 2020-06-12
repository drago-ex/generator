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
		$this->generator->runGenerate($input->getArgument('table'));
	}
}
