<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\BaseTestCase;


class XMLDatabaseTest extends BaseTestCase
{
	public function test_database_can_be_loaded(): void
	{
		$XMLDatabase = $this->container->getByType(\App\XMLDatabase::class);

		$this->assertIsArray($XMLDatabase->resource('resource')->findResourceMany());

		// Non-existing resource
		$this->assertEmpty($XMLDatabase->resource('resource')->findResourceMany());
		
		// Existing resource
		$this->assertNotEmpty($XMLDatabase->resource('employees')->findResourceMany());
	}
}
