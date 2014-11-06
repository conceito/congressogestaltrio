<?php
/**
 * Created by PhpStorm.
 * User: Conceito 11
 * Date: 29/07/14
 * Time: 14:27
 */

namespace Gestalt\Notifications;

use Cms\Notifications\AbstractNotification;

class JobWasUpdatedByUser extends AbstractNotification
{

	private $jobTitle = '';
	/**
	 * @return mixed
	 */
	public function send()
	{
		$this->setSubject("Trabalho atualizado");
		$this->service->adminCopy();
		return $this->service->send();
	}

	/**
	 * compose html body with template
	 * @return mixed
	 */
	public function messageBody()
	{
		$v['titulo'] = $this->jobTitle;
		$v['emailRespond'] = $this->config->item('email1');
		$body = $this->load->view('email/job_updated', $v, true);
		return $this->composeTemplate($body);
	}

	public function jobTitle($title = ''){
		$this->jobTitle = $title;
	}

	public function setUser($email, $nome = ''){
		$this->setUsers(array(
			array('nome' => $nome, 'email' => $email)
		));
	}
}