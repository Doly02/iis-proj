<?php

declare(strict_types=1);

require __DIR__ . '/src/vendor/autoload.php';

$configurator = App\Bootstrap::boot();
$configurator->setDebugMode(true);
$container = $configurator->createContainer();
$application = $container->getByType(Nette\Application\Application::class);
$application->run();
