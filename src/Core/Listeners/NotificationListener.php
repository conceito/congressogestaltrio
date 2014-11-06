<?php namespace Src\Core\Listeners;


class NotificationListener extends BaseEventListener{

	public function whenExampleWasFired($event)
	{


		d(get_called_class());
		d($event);

		return "Emailled";
	}

} 