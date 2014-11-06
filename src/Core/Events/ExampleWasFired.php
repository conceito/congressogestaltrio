<?php namespace Src\Core\Events;


class ExampleWasFired {


	private $data;

	function __construct($data)
	{
		$this->data = $data;
	}
}