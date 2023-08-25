<?php

declare(strict_types=1);

namespace App\Models;


/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property int $age
 * @property string $gender
 * @property string $created_at
 * @property string $updated_at
 */
class Employee extends BaseModel
{
	public const Genders = [
		'male' => 'Male',
		'female' => 'Female',
		'other' => 'Other',
	];
	
	public const AgeGroups = [
		[18, 20],
		[21, 30],
		[31, 45],
		[46, 60],
		[61, 75],
	];
	
	public const TableName = 'employees';
	
	protected array $columns = [
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
		'created_at' => 'date',
		'updated_at' => 'date',
	];

	/**
	 * Get formatted name.
	 * 
	 * @return string
	 */
	public function getFullName(): string
	{
		return $this->first_name . ' ' . $this->last_name;
	}

	/**
	 * Get formatted gender.
	 * 
	 * @return string
	 */
	public function getFormattedGender(): string
	{
		return self::Genders[$this->gender] ?? $this->gender;
	}
}
