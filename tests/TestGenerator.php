<?php

declare(strict_types = 1);

use Drago\Generator;
use Doctrine\Inflector;


class TestGenerator
{
	public function repository(): TestRepository
	{
		return new TestRepository;
	}


	public function options(): Generator\Options
	{
		return new Generator\Options;
	}


	private function helper(): Generator\Helpers
	{
		return new Generator\Helpers;
	}


	private function inflector(): Inflector\Inflector
	{
		$noopWordInflector = new Inflector\NoopWordInflector;
		$inflector = new Inflector\Inflector($noopWordInflector, $noopWordInflector);
		return $inflector;
	}


	public function testGenratorEntity(Generator\Repository $repository, Generator\Options $options): Generator\GeneratorEntity
	{
		return new Generator\GeneratorEntity($repository, $options, $this->inflector(), $this->helper());
	}


	/**
	 * @throws Dibi\Exception
	 */
	public function getGeneratorEntity(): Generator\GeneratorEntity
	{
		return new Generator\GeneratorEntity(
			$this->repository()->mysql(),
			$this->options(),
			$this->inflector(),
			$this->helper()
		);
	}
}
