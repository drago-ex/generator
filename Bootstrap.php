<?php

declare(strict_types=1);


class Bootstrap
{
	public static function boot(): Nette\Bootstrap\Configurator
	{
		$app = new Nette\Bootstrap\Configurator();
		$app->setDebugMode(true);
		$app->setTempDirectory(__DIR__ . '/');
		$app->addConfig(__DIR__ . '/config.neon');
		return $app;
	}
}
