<?php namespace Src\Product\Commands;


use Src\Commanding\BaseCommandHandler;

class DecorateAfterCommandHandler extends BaseCommandHandler {


	function __construct()
	{
	}

	public function handle($command)
	{
		d('decorate after');
		d($command->newAttrb);


		return $command;
	}
}