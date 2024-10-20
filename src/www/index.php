<?php

declare(strict_types=1);

// Autoloader Path
require __DIR__ . '/../vendor/autoload.php';

// Initialization of Nette Application
$configurator = new Nette\Bootstrap\Configurator;

// Configuration of Directory For Logs And Cache
$configurator->setTempDirectory(__DIR__ . '/../temp');

// Enablement of Debug Mode
$configurator->setDebugMode(true); //<! For Development Only

// Registration of Debugger
$configurator->enableTracy(__DIR__ . '/../log');

// Load of Configuration Files
$configurator->addConfig(__DIR__ . '/../config/common.neon');
$configurator->addConfig(__DIR__ . '/../config/services.neon');

// Creation of Application
$container = $configurator->createContainer();

// Launch
$container->getByType(Nette\Application\Application::class)->run();
