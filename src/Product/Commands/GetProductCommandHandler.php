<?php namespace Src\Product\Commands;


use Events;
use Src\Commanding\BaseCommandHandler;
use Src\Product\Events\ProductWasShown;
use Src\Product\Product;

class GetProductCommandHandler extends BaseCommandHandler {


	/**
	 * @var \Src\Product\Product
	 */
	private $productRepo;

	function __construct()
	{
		$this->productRepo = new Product();
	}

	public function handle($command)
	{

		$product = $this->productRepo->find($command->id);
		$product->newAttrb = $command->newAttrb;

		// fire event
		Events::trigger('product.events', new ProductWasShown($product));

		return $product;

	}

}