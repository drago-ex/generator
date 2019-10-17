<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();

$boot = new Nette\Configurator;
$boot->setTempDirectory(__DIR__ . '/temp');
$boot->createRobotLoader()
	->addDirectory(__DIR__ . '/Tests')
	->addDirectory(__DIR__ . '/../src')
	->register();

$boot->addConfig(__DIR__ . '/app.neon');
return $boot->createContainer();


function test(\Closure $function): void
{
	$function();
}
