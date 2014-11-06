<?php namespace Src\Core\Listeners;

class LogListener extends BaseEventListener
{


	public function whenExampleWasFired($event)
	{


		d(get_called_class());
		d($event);

		return 'Event logged';
	}

} 