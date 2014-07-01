<?php namespace Cms\Notifications;


class EvaluationCompletedNotification extends AbstractNotification{

	private $ci;
	protected $subject = 'Avaliação de trabalho concluída';

	private $evaluation;

	function __construct()
	{
		$this->ci = &get_instance();
	}


	/**
	 * @param mixed $evaluation
	 */
	public function setEvaluation($evaluation)
	{
		$this->evaluation = $evaluation;
	}

	/**
	 * @return mixed
	 */
	public function getEvaluation()
	{
		return $this->evaluation;
	}

	/**
	 * @return mixed
	 */
	public function send()
	{
		$this->setUsers(array(
			'nome' => $this->ci->config->item('title'),
			'email' => $this->ci->config->item('email1')
		));
		//		$this->service->adminCopy();
		$this->service->send();
	}

	/**
	 * compose html body with template
	 * @return mixed
	 */
	public function messageBody()
	{
		$v['eval'] = $this->getEvaluation();
		$body = $this->load->view('email/evaluation_completed', $v, true);

		return $this->composeTemplate($body);
	}
}