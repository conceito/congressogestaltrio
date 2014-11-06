<?php namespace Src\User\Listeners;



use Src\Core\Listeners\BaseEventListener;

class UserListener  extends BaseEventListener{

	public function whenUserWasUpdated($event)
	{
		return $event->user;
	}

} 