<?php

namespace Cms\Notifications;


use CI_Model;

abstract class AbstractNotification extends CI_Model  implements NotificationInterface
{
	protected $users;
	protected $fromEmail = null;
	protected $fromName = null;
	protected $subject = 'nenhum assunto';

	/**
	 * @var NotificationService
	 */
	protected $service;

	public function __construct()
	{
		$this->service = new NotificationSender($this);
	}


	/**
	 * By default just trigger the send() action.
	 * If you need do some work before send, overwrite this method
	 *
	 * @return bool
	 */
	public function send()
	{
		return $this->service->send();
	}




	/**
	 * array of users to receive notification
	 * @param $users
	 */
	public function setUsers($users)
	{
		if (!isset($users[0]))
		{
			$users = array($users);
		}

		$this->users = $users;
	}

	/**
	 * @return array
	 */
	public function getUsers()
	{
		return $this->users;
	}

	/**
	 * set sender email and name
	 *
	 * @param $email
	 * @param $name
	 * @return mixed
	 */
	public function setFrom($email, $name = null)
	{
		$this->fromEmail = $email;
		$this->fromName = $name;
	}

	public function getFromEmail()
	{
		if ($this->fromEmail === null)
		{
			return $this->config->item('email1');
		}

		return $this->fromEmail;
	}

	public function getFromName()
	{
		if ($this->fromName === null)
		{
			return $this->config->item('title');
		}

		return $this->fromName;
	}

	public function setSubject($subject)
	{
		$this->subject = $subject;
	}

	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * turn on debug mode
	 * @return mixed
	 */
	public function debug()
	{
		$this->service->setDebug(true);
	}

	/**
	 * read notification template view
	 *
	 * @param $body Body string
	 * @throws \Exception
	 * @return string
	 */
	public function composeTemplate($body)
	{
		if (!view_exist('template/email'))
		{
			throw new \Exception("E-mail view (template/email) does not exist.");
		}

		$v['body'] = $body;

		return $this->load->view('template/email', $v, true);
	}
} 