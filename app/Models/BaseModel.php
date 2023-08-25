<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\ModelHasCasts;


class BaseModel
{
	use ModelHasCasts;

	public const TableName = null;

	public const PrimaryKeyName = 'id';

	public const CreatedAtColumn = 'created_at';
	public const UpdatedAtColumn = 'updated_at';

	/** @var array<string,string> */
	protected array $attributes = [];

	/** @var array<string> */
	protected array $columns = [];

	/**
	 * @param array<string,string> $attributes
	 */
	public function __construct(array $attributes)
	{
		$this->initializeAttributes($attributes);
	}

	public function __get(string $name): mixed
	{
		return $this->getAttribute($name);
	}

	public function __set(string $name, mixed $value): void
	{
		if (in_array($name, $this->getFillables())) {
			$this->attributes[$name] = $value;
		}
	}

	public function getAttribute(string $name): mixed
	{
		if (array_key_exists($name, $this->attributes)) {
			if (method_exists($this, 'castValue') && array_key_exists($name, $this->getCasts())) {
				return $this->castValue($this->getCasts()[$name], $this->attributes[$name]);
			}

			return $this->attributes[$name];
		}

		return null;
	}

	public function setAttribute(string $name, mixed $value): void
	{
		if (in_array($name, $this->getFillables())) {
			$this->attributes[$name] = $value;
		} else {
			throw new \InvalidArgumentException("Attribute \"$name\" does not exist.");
		}
	}

	/**
	 * Get all attributes.
	 *
	 * @return array<string,mixed>
	 */
	public function getAttributes(): array
	{
		return $this->attributes;
	}

	/**
	 * Get fillable attributes.
	 * 
	 * @return array<string,mixed>
	 */
	public function getFillableAttributes(): array
	{
		return array_filter($this->attributes, function ($key) {
			return !in_array($key, [self::PrimaryKeyName]);
		}, ARRAY_FILTER_USE_KEY);
	}

	/**
	 * Get fillable attribute keys.
	 * 
	 * @return array<string>
	 */
	public function getFillables(): array
	{
		return array_filter($this->columns, function ($value) {
			return !in_array($value, [self::PrimaryKeyName, self::CreatedAtColumn, self::UpdatedAtColumn]);
		});
	}

	/**
	 * Set resource ID.
	 *
	 * @param int $id
	 * @return void
	 */
	public function setId(int $id): void
	{
		if (!$this->getAttribute(self::PrimaryKeyName)) {
			$this->attributes[self::PrimaryKeyName] = $id;
		}
	}

	/**
	 * Update timestamps columns.
	 * 
	 * @return void
	 */
	public function touch(): void
	{
		if (self::UpdatedAtColumn) {
			$this->attributes[self::UpdatedAtColumn] = date('Y-m-d H:i');
		}

		if (!$this->getAttribute(self::PrimaryKeyName) && self::CreatedAtColumn) {
			$this->attributes[self::CreatedAtColumn] = date('Y-m-d H:i');
		}
	}

	/**
	 * @param array<string,mixed> $attributes
	 * @return void
	 */
	protected function initializeAttributes(array $attributes): void
	{
		foreach ($this->columns as $column) {
			$this->attributes[$column] = $attributes[$column] ?? null;
		}
	}
}
