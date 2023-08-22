<?php

declare(strict_types=1);

namespace App\Repositories;


use App\Interfaces\Repository\IEmployeeRepository;
use App\Models\Employee;
use App\XMLDatabase;
use Exception;

class EmployeeRepository implements IEmployeeRepository
{
	protected string $resource = Employee::TableName;

	protected string $primaryKeyName = Employee::PrimaryKeyName;

	public function __construct(
		private XMLDatabase $XMLDatabase
	) {
	}

	/**
	 * Get all employees.
	 *
	 * @param string $orderBy
	 * @param string $orderDir
	 * @return array<Employee>
	 * @throws Exception
	 */
	public function getAll(string $orderBy = 'id', string $orderDir = 'asc'): array
	{	
		$rows = $this->XMLDatabase->resource($this->resource, $this->primaryKeyName)->findResourceMany();

		$models = [];
		foreach ($rows as $row) {
			$models[] = new Employee($row);
		}
		
		$models = $this->orderResource($models, $orderBy, $orderDir);

		return $models;
	}

	/**
	 * Find employee by ID.
	 *
	 * @param int $id
	 * @return Employee|null
	 * @throws Exception
	 */
	public function findById(int $id): ?Employee
	{
		$row = $this->XMLDatabase->resource($this->resource, $this->primaryKeyName)->findResource($id);

		if (!$row) {
			return null;
		}

		return new Employee($row);
	}

	/**
	 * Order resource.
	 * 
	 * @param array $rows
	 * @param string $orderBy
	 * @param mixed $orderDir
	 * @return array<Employee>
	 */
	private function orderResource(array $rows, string $orderBy, mixed $orderDir): array
	{
		usort($rows, function ($a, $b) {
			return $a->id - $b->id;
		});

		return $rows;
	}
}
