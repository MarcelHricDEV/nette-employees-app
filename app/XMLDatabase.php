<?php


namespace App;

use App\Models\BaseModel;
use DOMDocument;
use DOMElement;
use DOMException;
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
	protected function getResource(bool $asArray = true): array
	{
		$rootNode = $this->getDataSource()->documentElement;

		/** @var DOMNode $resource */
		foreach ($rootNode->childNodes as $resource) {
			if ($resource->nodeName !== $this->resource) {
				continue;
			}

			return $asArray ? $this->XMLService->toArray($resource) : $resource;
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

	/**
	 * Save resource.
	 *
	 * @param BaseModel $model
	 * @return BaseModel|null
	 * @throws DOMException
	 */
	public function save(BaseModel $model): ?BaseModel
	{
		$source = $this->getDataSource();

		$rootNode = $source->documentElement;

		$model->touch();

		/** @var DOMNode $resource */
		foreach ($rootNode->childNodes as $resource) {
			if ($resource->nodeName !== $this->resource) {
				continue;
			}

			$maxId = 0;

			/** @var DOMElement $row */
			foreach ($resource->childNodes as $row) {
				$resourceId = $row->getAttribute($this->primaryKeyName);

				if ($resourceId && (int)$resourceId > $maxId) {
					$maxId = $resourceId;
				}

				if ($resourceId === (string)$model->getAttribute($this->primaryKeyName)) {
					return $this->updateResource($source, $row, $model);
				}
			}

			return $this->saveResource($source, $resource, $model, $maxId + 1);
		}

		return null;
	}

	/**
	 * Delete resource.
	 *
	 * @param int $id
	 * @return bool
	 */
	public function delete(int $id): bool
	{
		$source = $this->getDataSource();

		$rootNode = $source->documentElement;

		/** @var DOMNode $resource */
		foreach ($rootNode->childNodes as $resource) {
			if ($resource->nodeName !== $this->resource) {
				continue;
			}

			/** @var DOMElement $row */
			foreach ($resource->childNodes as $row) {
				$resourceId = $row->getAttribute($this->primaryKeyName);

				if ($resourceId === (string)$id) {
					$resource->removeChild($row);

					$source->save($this->XMLService->getPath($this->filename));

					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param DOMDocument $source
	 * @param DOMNode $resource
	 * @param BaseModel $model
	 * @param int $id
	 * @return BaseModel
	 * @throws DOMException
	 */
	private function saveResource(DOMDocument $source, DOMNode $resource, BaseModel $model, int $id): BaseModel
	{
		$model->setId($id);

		// Create wrapper node
		$node = $source->createElement('item');
		$node->setAttribute($this->primaryKeyName, (string)$model->getAttribute($this->primaryKeyName));

		// Append nodes with attributes
		foreach ($model->getFillableAttributes() as $key => $value) {
			$node->appendChild($source->createElement($key, !is_null($value) ? $value : ''));
		}

		$resource->appendChild($node);

		$source->save($this->XMLService->getPath($this->filename));

		return $model;
	}

	/**
	 * @param DOMDocument $source
	 * @param DOMNode $row
	 * @param BaseModel $model
	 * @return BaseModel
	 * @throws DOMException
	 */
	private function updateResource(DOMDocument $source, DOMNode $row, BaseModel $model): BaseModel
	{
		$row->nodeValue = null;

		foreach ($model->getFillableAttributes() as $key => $value) {
			$row->appendChild($source->createElement($key, !is_null($value) ? $value : ''));
		}

		$source->save($this->XMLService->getPath($this->filename));

		return $model;
	}
}
