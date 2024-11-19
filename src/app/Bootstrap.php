<?php

declare(strict_types=1);

use Nette\Configurator;

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Configurator;

// Enable Tracy debugger (set to false or remove in production)
$configurator->setDebugMode(true);
$configurator->enableTracy(__DIR__ . '/../log');

// Set the temporary directory for cache and other temporary files
$configurator->setTempDirectory(__DIR__ . '/../temp');

// Register RobotLoader for automatic class loading
$configurator->createRobotLoader()
    ->addDirectory(__DIR__)
    ->register();

// Load your configuration files
$configurator->addConfig(__DIR__ . '/../config/common.neon');
$configurator->addConfig(__DIR__ . '/../config/services.neon');

// Create and return the DI container
return $configurator->createContainer();
