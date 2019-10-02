<?php

declare(strict_types = 1);

/**
 * Drago Generator
 * Package built on Nette Framework
 */
namespace Drago\Generator;

use Dibi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Command for generate entity.
 */
class GeneratorCommand extends Command
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
		$this
			->setName('generate:entity')
			->setDescription('Generating entity from database.')
			->addArgument('table', InputArgument::OPTIONAL);
	}


	/**
	 * Executes the current command.
	 * @throws Dibi\Exception
	 */
	protected function execute(InputInterface $input, OutputInterface $output): void
	{
		$this
			->generator
			->runGenerate($input->getArgument('table'));
	}
}
