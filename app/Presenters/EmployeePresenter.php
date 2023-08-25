<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Forms\EmployeeFormFactory;
use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use App\Services\ViteService;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;


final class EmployeePresenter extends BasePresenter
{
	public function __construct(
		ViteService $viteService,
		private readonly EmployeeRepository $employeeRepository,
		private readonly EmployeeFormFactory $employeeFormFactory
	) {
		parent::__construct($viteService);
	}

	/**
	 * @throws \Exception
	 */
	public function renderDefault(): void
	{
		$this->template->employees = $this->employeeRepository->getAll();
	}

	/**
	 * @throws \Exception
	 */
	public function renderCharts(): void
	{
		$this->template->employeesAgeChart = $this->employeeRepository->getCountByAgeRanges();
	}

	/**
	 * @throws \Exception
	 */
	public function renderEdit(string $id): void
	{
		$employee = $this->employeeRepository->findById((int)$id);

		if (!$employee) {
			$this->redirect('default');
		}

		$this->getComponent('editForm')->setDefaults($employee->getAttributes());

		$this->template->employee = $employee;
	}

	public function renderCreate(): void
	{

	}

	public function handleDelete(string $id): void
	{
		$this->employeeRepository->delete((int)$id);
		
		$this->redirect('default');
	}

	public function createComponentEditForm(): Form
	{
		$form = $this->employeeFormFactory->create();

		$form->addHidden('id');

		$form->onSuccess[] = [$this, 'editFormSucceeded'];

		return $form;
	}

	public function createComponentCreateForm(): Form
	{
		$form = $this->employeeFormFactory->create();

		$form->onSuccess[] = [$this, 'createFormSucceeded'];

		return $form;
	}

	/**
	 * @param Form $form
	 * @param ArrayHash<string> $data
	 * @return void
	 * @throws \DOMException
	 * @throws \Nette\Application\AbortException
	 */
	public function editFormSucceeded(Form $form, ArrayHash $data): void
	{
		$employee = $this->employeeRepository->findById((int)$data['id']);

		if (!$employee) {
			$form->addError('Employee not found.');
			return;
		}

		foreach ($employee->getFillables() as $attribute) {
			if (isset($data[$attribute])) {
				$employee->setAttribute($attribute, $data[$attribute]);
			}
		}

		$this->employeeRepository->save($employee);
		
		$this->redirect('default');
	}

	/**
	 * @param Form $form
	 * @param ArrayHash<string> $data
	 * @return void
	 * @throws \DOMException
	 * @throws \Nette\Application\AbortException
	 */
	public function createFormSucceeded(Form $form, ArrayHash $data): void
	{
		$employee = new Employee((array)$data);

		$this->employeeRepository->save($employee);

		$this->redirect('default');
	}
}
