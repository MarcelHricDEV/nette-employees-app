<?php

declare(strict_types=1);

namespace App\Models;


/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property int $age
 * @property int $gender
 * @property string $created_at
 * @property string $updated_at
 */
class Employee extends BaseModel
{
	public const TableName = 'employees';
	
	protected array $attributes = [
		self::PrimaryKeyName,
		'first_name',
		'last_name',
		'age',
		'gender',
		'created_at',
		'updated_at',
	];
	
	protected array $casts = [
		'age' => 'int',
		'gender' => 'int',
	];
}
