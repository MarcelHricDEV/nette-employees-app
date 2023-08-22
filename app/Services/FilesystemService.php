<?php

declare(strict_types=1);

namespace App\Services;

use Nette\Utils\FileSystem;


class FilesystemService
{
	public function __construct(
		private string $basePath
	) {
	}

	public function getStoragePath(string $path = null): string
	{
		return FileSystem::joinPaths($this->basePath, $path);
	}
}
