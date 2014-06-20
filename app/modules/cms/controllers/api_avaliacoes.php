<?php

class Api_avaliacoes extends Api_Controller
{


	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms/avaliacao_model', 'avaliacao');
	}

	/**
	 * GET
	 */
	public function all()
	{
		$vars = $this->uri->to_array(array('contentId'));

		$contentId = $vars['contentId'];

		$finished = $this->avaliacao->finished($contentId);
		$awaiting = $this->avaliacao->awaiting($contentId);

		echo $this->responseOk(array(
			'finished' => $finished,
			'awaiting' => $awaiting
		), 'Avaliações retornadas com sucesso.');
	}


	public function remove($id)
	{
		try{
			$ret = $this->avaliacao->remove($id);
			echo $this->responseOk(array('id' => $id), 'Avaliação removida.');

		} catch(Exception $e)
		{
			echo $this->responseError($e->getMessage());
		}


	}
}