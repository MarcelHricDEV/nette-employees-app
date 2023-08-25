<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\BaseModel;
use DateTime;


/**
 * @mixin BaseModel
 */
trait ModelHasCasts
{
	/** @var array<string,string> */
	protected array $defaultCasts = [
		self::PrimaryKeyName => 'int',
	];

	/** @var array<string,string> */
	protected array $casts = [];

	/**
	 * Cast attribute value.
	 * 
	 * @param string $name
	 * @param string $value
	 * @return mixed
	 */
	protected function castValue(string $name, mixed $value): mixed
	{
		try {
			switch ($name) {
				case 'int':
					return (int)$value;
				case 'float':
					return (float)$value;
				case 'date':
					return new DateTime($value);
				case 'string':
				default:
					return $value;
			}		
		} catch (\Exception $e) {
			throw new \Exception("Attribute with value \"$value\" is not castable to \"$name\"");
		}
	}

	/**
	 * Get merged casts.
	 * 
	 * @return array<string>
	 */
	protected function getCasts(): array
	{
		return array_merge($this->casts, $this->defaultCasts);
	}
}
