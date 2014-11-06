<?php namespace Src\Product\Events;


class ProductWasShown {


	public $product;

	function __construct($product)
	{
		$this->product = $product;
	}
}