<?php namespace Src\Presenters;


class FilePresenter extends Presenter{

	public function thumb()
	{
		return thumb($this->entity->nome);
	}

	public function med()
	{
		return med($this->entity->nome);
	}

	public function max()
	{
		return grande($this->entity->nome);
	}

} 