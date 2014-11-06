<?php namespace Src\Core\Listeners;

/**
 * Class BaseEventListener
 *
 * basic event listener
 *
 * @package App\Listeners
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2014 Bruno Barros
 */
class BaseEventListener
{


	public function handle($event)
	{
		$eventName   = $this->getEventName($event);
		$eventMethod = $this->getEventMethod($eventName);

		if ($this->isListenerRegistered($eventName))
		{
			return call_user_func(array($this, $eventMethod), $event);
		}
	}

	private function getEventName($event)
	{
		return class_basename($event);
	}

	private function isListenerRegistered($eventName)
	{
		return method_exists($this, $this->getEventMethod($eventName));
	}

	private function getEventMethod($eventName)
	{
		return "when{$eventName}";
	}

}