<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\ModelHasCasts;
use Exception;


class BaseModel
{
	use ModelHasCasts;

	public const TableName = null;

	public const PrimaryKeyName = 'id';

	/** @var array<string,string> */
	protected array $values = [];

	/** @var array<string> */
	protected array $attributes = [];

	/**
	 * @param array<string,string> $values
	 * @throws Exception
	 */
	public function __construct(array $values)
	{
		if (!array_key_exists(self::PrimaryKeyName, $values)) {
			throw new Exception('Model has no primary key set.');
		}
		
		$this->values = $values;
	}

	public function __get(string $name): mixed
	{
		if (array_key_exists($name, $this->values)) {
			if (method_exists($this, 'castValue') && array_key_exists($name, $this->getCasts())) {
				return $this->castValue($this->getCasts()[$name], $this->values[$name]);
			}
			
			return $this->values[$name];
		}

		return null;
	}

	public static function getPrimaryKeyName(): string
	{
		return 'id';
	}
}
