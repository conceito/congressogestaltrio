<?php namespace Src\User\Events;


class UserWasUpdated {


	public  $user;

	function __construct($user)
	{
		$this->user = $user;
	}
}