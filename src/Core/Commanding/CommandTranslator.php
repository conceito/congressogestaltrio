<?php namespace Src\Core\Commanding;


use Exception;

class CommandTranslator {

	public function toCommandHandler($command)
	{
		$handler = get_class($command) . 'Handler';

		if(! class_exists($handler))
		{
			throw new Exception("Command handler [$handler] does not exists.");
		}

		return $handler;
	}

} 