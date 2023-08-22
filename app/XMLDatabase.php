<?php


namespace App;

use DOMDocument;
use DOMNode;


class XMLDatabase
{
	protected string $filename = 'database.xml';

	protected string $resource;

	protected string $primaryKeyName;

	public function __construct(
		protected readonly \App\Services\XMLService $XMLService
	) {
	}

	public function resource(string $resource, string $primaryKeyName = 'id'): self
	{
		$this->resource = $resource;
		$this->primaryKeyName = $primaryKeyName;
		
		return $this;
	}

	/**
	 * Get database XML content.
	 * 
	 * @return DOMDocument
	 */
	protected function getDataSource(): DOMDocument
	{
		return $this->XMLService->loadFile($this->filename);
	}

	/**
	 * Get resource array from XML file.
	 * 
	 * @return array<array<string, string>>
	 */
	protected function getResource(): array
	{
		$rootNode = $this->getDataSource()->documentElement;

		/** @var DOMNode $resource */
		foreach ($rootNode->childNodes as $resource) {
			if ($resource->nodeName !== $this->resource) {
				continue;
			}

			return $this->XMLService->toArray($resource);
		}

		return [];
	}

	/**
	 * Get all resource rows.
	 * 
	 * @return array<string, array<string, string>>
	 */
	public function findResourceMany(): array
	{
		return $this->getResource();
	}

	/**
	 * Get single resource row.
	 * 
	 * @param int $id
	 * @return array<string, string>|null
	 */
	public function findResource(int $id): ?array
	{
		foreach ($this->getResource() as $row) {
			if ((int)$row[$this->primaryKeyName] === $id) {
				return $row;
			}
		}
		
		return null;
	}
}
