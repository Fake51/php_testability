<?php
namespace edsonmedina\php_testability\Contexts;

use edsonmedina\php_testability\AbstractContext;

class ClassContext extends AbstractContext
{
	public function __construct ($name)
	{
		$this->name = $name;
	}
}