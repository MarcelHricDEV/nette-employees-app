<?php

declare(strict_types=1);

namespace App\Forms;

use App\Models\Employee;
use Nette\Application\UI\Form;


class EmployeeFormFactory
{
	public function create(): Form
	{
		$form = new Form();

		$form->addText('first_name', 'First name')
			->setRequired('First name is required.')
			->addRule($form::Pattern, 'First name cannot include special characters','^[\w]*')
			->addRule($form::MaxLength, 'First name cannot be longer than %d.', 100);
		
		$form->addText('last_name', 'Last name')	
			->setRequired('Last name is required.')
			->addRule($form::Pattern, 'Last name cannot include special characters','^[\w]*')
			->addRule($form::MaxLength, 'First name cannot be longer than %d.', 100);
		$form->addInteger('age', 'Age')
			->setRequired('Age is required.')
			->addRule($form::Min, 'Min age is %d.', 18)
			->addRule($form::Max, 'Max age is %d.', 75);
		$form->addSelect('gender', 'Gender', Employee::Genders);
		$form->addSubmit('submit', 'Save');
		
		return $form;
	}
}
