<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

define('TYPE_STRING', 'string');
define('TYPE_BOOL', 'bool');
define('TEMP_DIR', __DIR__ . '/tmp');

@mkdir(dirname(TEMP_DIR));
@mkdir(TEMP_DIR);

$boot = new Nette\Configurator;
$boot->setTempDirectory(TEMP_DIR);
$boot->createRobotLoader()
	->addDirectory(__DIR__)
	->addDirectory(__DIR__ . '/../src')
	->register();

return $boot->createContainer();


function test(Closure $function): void
{
	$function();
}
