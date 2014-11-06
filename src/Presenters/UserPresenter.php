<?php namespace Src\Presenters;


class UserPresenter extends Presenter{

	public function nome()
	{
		return $this->entity->nome . " - presented";
	}

} 