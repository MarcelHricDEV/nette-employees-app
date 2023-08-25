<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Repositories\EmployeeRepository;
use App\Services\ViteService;
use App\Services\XMLService;
use Nette\Application\AbortException;


final class HomePresenter extends BasePresenter
{
	public function __construct(
		ViteService $viteService
	) {
		parent::__construct($viteService);
	}

	/**
	 * @throws AbortException
	 */
	public function actionDefault(): void
	{
		$this->redirect('Employee:default');
	}
}
