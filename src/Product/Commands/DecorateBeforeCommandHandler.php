<?php namespace Src\Product\Commands;


use Src\Commanding\BaseCommandHandler;

class DecorateBeforeCommandHandler extends BaseCommandHandler {


	function __construct()
	{
	}

	public function handle($command)
	{
		d('decorate before');
		d($command->id);

		$command->newAttrb = "new value";


		return $command;
	}
}