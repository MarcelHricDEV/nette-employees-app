<?php

declare(strict_types=1);

namespace App\Repositories;


use App\Interfaces\Repository\IEmployeeRepository;
use App\Models\BaseModel;
use App\Models\Employee;
use App\XMLDatabase;
use DOMException;
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
	 * Save employee to XML database.
	 *
	 * @param Employee $employee
	 * @return BaseModel
	 * @throws DOMException
	 */
	public function save(Employee $employee): BaseModel
	{
		return $this->XMLDatabase->resource($this->resource, $this->primaryKeyName)->save($employee);
	}

	/**
	 * Delete employee.
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function delete(int $id): bool
	{
		return $this->XMLDatabase->resource($this->resource, $this->primaryKeyName)->delete($id);
	}

	/**
	 * Get employees count by age groups.
	 *
	 * @return array<string,int>
	 * @throws Exception
	 */
	public function getCountByAgeRanges(): array
	{
		$result = [];

		foreach (Employee::AgeGroups as $values) {
			$result[$values[0] . '-' . $values[1]] = 0;
		}

		foreach ($this->getAll() as $row) {
			$age = $row->getAttribute('age');

			foreach (Employee::AgeGroups as $values) {
				if ($age >= $values[0] && $age <= $values[1]) {
					$result[$values[0] . '-' . $values[1]] += 1;
				}
			}
		}

		return $result;
	}
	
	/**
	 * Order resource.
	 * 
	 * @param array<Employee> $rows
	 * @param string $orderBy
	 * @param mixed $orderDir
	 * @return array<Employee>
	 */
	private function orderResource(array $rows, string $orderBy, mixed $orderDir): array
	{
		usort($rows, function (BaseModel $a, BaseModel $b) use ($orderBy, $orderDir) {
			if ($orderDir === 'asc') {
				return $a->getAttribute($orderBy) - $b->getAttribute($orderBy);
			}

			return $b->getAttribute($orderBy) - $a->getAttribute($orderBy);
			
		});

		return $rows;
	}
}
