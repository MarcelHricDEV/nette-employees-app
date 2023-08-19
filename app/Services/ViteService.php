<?php

/**
 * @see https://blog.nette.org/cs/nette-vite-pouziti-nette-s-vite-pro-rychly-lokalni-vyvoj
 * @see https://github.com/lubomirblazekcz/nette-vite
 * @author https://github.com/lubomirblazekcz, https://github.com/MarcelHricSK
 */

namespace App\Services;

use Exception;
use Nette\Utils\FileSystem;
use Nette\Utils\Html;
use Nette\Utils\Json;


class ViteService
{
	private const BaseUrl = '/';

	public function __construct(
		private string $viteServer,
		private string $manifestFile,
		private bool $productionMode,
	) {
	}

	/**
	 * @throws \Nette\Utils\JsonException
	 * @throws \Exception
	 */
	public function getAsset(string $entrypoint): string
	{
		// Handle dev state
		if ($this->isEnabled()) {
			return $this->viteServer . '/' . $entrypoint;
		}

		// Handle production state
		if (!file_exists($this->manifestFile)) {
			throw new Exception('Missing manifest file: ' . $this->manifestFile);
		}

		$manifest = Json::decode(FileSystem::read($this->manifestFile), true);
		$asset = $manifest[$entrypoint]['file'];

		return self::BaseUrl . $asset;
	}

	/**
	 * @return array<string>
	 * @throws \Nette\Utils\JsonException
	 */
	public function getCssAssets(string $entrypoint): array
	{
		// Handle dev state
		if ($this->isEnabled()) {
			return [];
		}

		// Handle production state
		if (!file_exists($this->manifestFile)) {
			throw new Exception('Missing manifest file: ' . $this->manifestFile);
		}

		$assets = [];
		$manifest = Json::decode(FileSystem::read($this->manifestFile), true);
		foreach ($manifest[$entrypoint]['css'] ?? [] as $asset) {
			$assets[] = self::BaseUrl . $asset;
		}

		return $assets;
	}

	public function isEnabled(): bool
	{
		if (!$this->productionMode) {
			return true;
		}

		return false;
	}

	/**
	 * @return array<\Nette\Utils\Html>
	 * @throws \Nette\Utils\JsonException
	 */
	public function getTags(string $entrypoint): array
	{
		$scripts = [$this->getAsset($entrypoint)];
		$styles = $this->getCssAssets($entrypoint);

		$tags = [];
		if ($this->isEnabled()) {
			$tags[] = Html::el('script')->type('module')->src($this->viteServer . '/' . '@vite/client');
		}

		foreach ($styles as $path) {
			$tags[] = Html::el('link')->rel('stylesheet')->href($path);
		}

		foreach ($scripts as $path) {
			$tags[] = Html::el('script')->type('module')->src($path);
		}

		return $tags;
	}

	/**
	 * @throws \Nette\Utils\JsonException
	 */
	public function printTags(string $entrypoint): void
	{
		foreach ($this->getTags($entrypoint) as $tag) {
			echo $tag;
		}
	}
}
