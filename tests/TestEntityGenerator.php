<?php

declare(strict_types = 1);

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\NoopWordInflector;
use Drago\Generator\EntityGenerator;
use Drago\Generator\Options;
use Drago\Generator\Repository;


class TestEntityGenerator
{
	public function repository(): TestRepository
	{
		return new TestRepository;
	}


	public function options(): Options
	{
		return new Options;
	}


	private function inflector(): Inflector
	{
		$noopWordInflector = new NoopWordInflector;
		return new Inflector($noopWordInflector, $noopWordInflector);
	}


	public function createGeneratorEntity(Repository $repository, Options $options): EntityGenerator
	{
		return new EntityGenerator($repository, $options, $this->inflector());
	}
}
