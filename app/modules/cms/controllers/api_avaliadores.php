<?php

/**
 * Class Api_avaliadores
 *
 * AJAX calls to Evaluators
 */
class Api_avaliadores extends Api_Controller
{


	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms/avaliador_model', 'avaliador');
		$this->load->model('cms/avaliacao_model', 'avaliacao');
	}

	public function all()
	{

		$all = $this->avaliador->all();

		if ($all)
		{
			echo $this->responseOk($all, 'Avaliadores encontrados.');
		}
		else
		{
			echo $this->responseError('Nenhum avaliador disponível.');
		}
	}


	/**
	 * send invite to evaluation a job
	 */
	public function invite()
	{
		$data  = file_get_contents('php://input');
		$array = json_decode($data, true);

		$jobId = (isset($array['jobid'])) ? $array['jobid'] : null;
		$userId = (isset($array['userid'])) ? $array['userid'] : null;

		//		echo $this->responseOk($array, 'dados.');
		//		exit;

		try
		{
			$evaluationId = $this->avaliador->sendInvite($jobId, $userId);

			if($evaluationId){

				$evaluation = $this->avaliacao->find($evaluationId);
				echo $this->responseOk($evaluation, 'Convite enviado.');
			} else {
				echo $this->responseError("Houve um erro ao enviar avaliação");
			}
		} catch (Exception $e)
		{
			echo $this->responseError($e->getMessage());
		}

	}
}