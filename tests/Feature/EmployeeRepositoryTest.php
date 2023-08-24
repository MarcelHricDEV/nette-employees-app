<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Employee;
use Tests\BaseTestCase;


class EmployeeRepositoryTest extends BaseTestCase
{
	public function test_all_employees_can_be_loaded(): void
	{
		$employeeRepository = $this->container->getByType(\App\Repositories\EmployeeRepository::class);

		$result = $employeeRepository->getAll();
		
		$this->assertIsArray($result);
		$this->assertNotEmpty($result);
	}
	
	public function test_employees_can_be_found_by_id(): void
	{
		$employeeRepository = $this->container->getByType(\App\Repositories\EmployeeRepository::class);

		$result = $employeeRepository->findById(1);
		
		$this->assertNotNull($result);
		$this->assertInstanceOf(Employee::class, $result);
	}
	
	public function test_non_existent_employees_cannot_be_found_by_id(): void
	{
		$employeeRepository = $this->container->getByType(\App\Repositories\EmployeeRepository::class);

		$result = $employeeRepository->findById(100);
		
		$this->assertNull($result);
	}
}
