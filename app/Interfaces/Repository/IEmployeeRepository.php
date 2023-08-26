<?php

declare(strict_types=1);

namespace App\Interfaces\Repository;

use App\Models\Employee;


interface IEmployeeRepository
{
	public function getAll(string $orderBy = 'id', string $orderDir = 'asc'): array;

	public function findById(int $id): ?Employee;
}
