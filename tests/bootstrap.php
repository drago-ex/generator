<?php

declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();


function test(\Closure $function): void
{
	$function();
}
