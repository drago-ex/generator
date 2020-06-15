<?php

declare(strict_types = 1);

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\NoopWordInflector;
use Drago\Generator\GeneratorEntity;
use Drago\Generator\Helpers;
use Drago\Generator\Options;
use Drago\Generator\Repository;


class TestGenerator
{
	public function repository(): TestRepository
	{
		return new TestRepository;
	}


	public function options(): Options
	{
		return new Options;
	}


	private function helper(): Helpers
	{
		return new Helpers;
	}


	private function inflector(): Inflector
	{
		$noopWordInflector = new NoopWordInflector;
		return new Inflector($noopWordInflector, $noopWordInflector);
	}


	public function createGeneratorEntity(Repository $repository, Options $options): GeneratorEntity
	{
		return new GeneratorEntity($repository, $options, $this->inflector(), $this->helper());
	}
}
