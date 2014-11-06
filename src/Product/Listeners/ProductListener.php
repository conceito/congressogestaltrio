<?php namespace Src\Product\Listeners;


use Src\Commanding\CommandBus;
use Src\Listeners\BaseEventListener;
use Src\Product\Commands\DecorateCommand;

class ProductListener extends BaseEventListener{

	private $bus;

	function __construct()
	{
		$this->bus = CommandBus::make();
	}

	public function whenProductWasShown($event)
	{
		d('event');
		d($event->product->titulo);



		return $event->product;
	}
} 