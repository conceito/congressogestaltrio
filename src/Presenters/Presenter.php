<?php namespace Src\Presenters;


abstract class Presenter {


	protected $entity;

	function __construct($entity)
	{
		$this->entity = $entity;
	}

	public function __get($name)
	{
		if(method_exists($this, $name))
		{
			return $this->$name();
		}

		return $this->entity->$name;
	}
}