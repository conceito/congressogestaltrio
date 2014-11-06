<?php namespace Gestalt\Trabalho;


class EvaluationUnserializer {


	private $serialized;

	private $unserialized = array();

	function __construct($serialized)
	{
		$this->serialized = $serialized;
		$this->unserialized = $this->preParser($serialized);
	}

	public function get()
	{
	return $this->unserialized;
	}

	public function getComments()
	{
		return (isset($this->unserialized['q14'])) ? $this->unserialized['q14'] : '';
	}

	private function preParser($serialized)
	{
		return unserialize($serialized);
	}
}