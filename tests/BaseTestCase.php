<?php

declare(strict_types=1);

namespace Tests;


class BaseTestCase extends \PHPUnit\Framework\TestCase
{
	protected \Nette\DI\Container $container;

	public function __construct(string $name)
	{
		parent::__construct($name);

		$this->container = \App\Bootstrap::bootTesting()->createContainer();
	}
}
