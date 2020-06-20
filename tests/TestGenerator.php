<?php

declare(strict_types = 1);

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\NoopWordInflector;
use Drago\Generator\EntityGenerator;
use Drago\Generator\FormDataGenerator;
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


	private function inflector(): Inflector
	{
		$noopWordInflector = new NoopWordInflector;
		return new Inflector($noopWordInflector, $noopWordInflector);
	}


	public function createEntityGenerator(Repository $repository, Options $options): EntityGenerator
	{
		return new EntityGenerator($repository, $options, $this->inflector());
	}


	public function createFormDataGenerator(Repository $repository, Options $options): FormDataGenerator
	{
		return new FormDataGenerator($repository, $options, $this->inflector());
	}
}
