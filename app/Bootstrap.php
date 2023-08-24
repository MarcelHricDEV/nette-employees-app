<?php

declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;


class Bootstrap
{
	private static function createBaseConfigurator($appDir): Configurator
	{
		$configurator = new Configurator;

		// $configurator->setDebugMode('secret@23.75.345.200'); // enable for your remote IP
		$configurator->enableTracy($appDir . '/log');

		$configurator->setTempDirectory($appDir . '/temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator->addConfig($appDir . '/config/common.neon');
		$configurator->addConfig($appDir . '/config/services.neon');
		$configurator->addConfig($appDir . '/config/local.neon');
		
		return $configurator;
	}
	
	public static function boot(): Configurator
	{
		$appDir = dirname(__DIR__);

		$configurator = self::createBaseConfigurator($appDir);

		return $configurator;
	}

	public static function bootTesting(): Configurator
	{
		$appDir = dirname(__DIR__);
		
		$configurator = self::createBaseConfigurator($appDir);
		
		$configurator->addConfig($appDir . '/config/testing.neon');

		return $configurator;
	}
}
