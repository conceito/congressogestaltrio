<?php namespace Gestalt\Events;


use Src\Core\Listeners\BaseEventListener;

class UserListener  extends BaseEventListener{

	public function whenUserSignedUpAtLunch($event)
	{
		$user = $event->user;
		dd("event UserSignedUpAtLunch");
	}

} 