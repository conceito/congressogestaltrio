<?php namespace Gestalt\Events;


class UserSignedUpAtLunch {


	public $user;

	function __construct($user)
	{
		$this->user = $user;
	}
}