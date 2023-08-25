<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;

		$router->addRoute('/', 'Home:default');
		
		// Employees
		$router->addRoute('employee', 'Employee:default');
		$router->addRoute('employee/create', 'Employee:create');
		$router->addRoute('employee/<id>/edit', 'Employee:edit');
		$router->addRoute('employee/charts', 'Employee:charts');

		return $router;
	}
}
