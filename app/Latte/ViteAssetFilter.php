<?php

declare(strict_types=1);

namespace App\Latte;

use App\Services\ViteService;


/**
 * @see https://blog.nette.org/cs/nette-vite-pouziti-nette-s-vite-pro-rychly-lokalni-vyvoj
 * @see https://github.com/lubomirblazekcz/nette-vite
 * @author https://github.com/lubomirblazekcz, https://github.com/MarcelHricSK
 */
class ViteAssetFilter
{
	public function __construct(
		private ViteService $viteService,
	) {
	}

	/**
	 * @throws \Nette\Utils\JsonException
	 * @throws \Exception
	 */
	public function __invoke(string $path): string
	{
		return $this->viteService->getAsset($path);
	}
}
