<?php namespace Src\Product\Commands;


class GetProductCommand {

	public $id;

	function __construct($id)
	{
		$this->id = $id;
	}

} 