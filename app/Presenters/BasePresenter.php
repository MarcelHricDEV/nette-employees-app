<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Services\ViteService;
use Nette;


class BasePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private ViteService $viteService
    ) {
	}

	public function beforeRender(): void
    {
        $this->template->vite = $this->viteService;
    }
}
