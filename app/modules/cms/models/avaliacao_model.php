<?php

class Avaliacao_model extends MY_Model
{

	/**
	 * store the allByContent()
	 * @var null
	 */
	protected $evaluations = null;



	public function __construct()
	{
		$this->load->library(array('cms_usuario'));
		$this->load->model('cms/trabalhos_model', 'trabalho');
		$this->load->model('cms/avaliador_model', 'avaliador');
	}

	public function find($id)
	{

	}

	public function allByContent($contentId)
	{
		if($this->evaluations !== null)
		{
			return $this->evaluations;
		}



	}


	public function finished()
	{


	}

	public function awaiting()
	{

	}

	public function allByUser($userId)
	{

	}

}