<?php

declare(strict_types = 1);

/**
 * Drago Extension
 * Package built on Nette Framework
 */

namespace Drago\Generator\Command;

use Drago\Generator\FormDataGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;


/**
 * Command for generate form data.
 */
class FormDataCommand extends Command
{
	protected static $defaultName = 'make:formData';
	private FormDataGenerator $formDataGenerator;


	public function __construct(FormDataGenerator $formDataGenerator)
	{
		parent::__construct();
		$this->formDataGenerator = $formDataGenerator;
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
		$this->formDataGenerator->runGeneration($input->getArgument('table'));
		$output->writeln('Generation was successful.');
	}
}
