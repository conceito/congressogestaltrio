<?php
namespace Cms\Notifications;

/**
 * Class NotificationService
 *
 * get message, users and send messages
 *
 * <code>
 * $this->service->adminCopy(); // optional
 * $this->service->send();
 * </code>
 *
 * @package Cms\Notifications
 */
class NotificationSender
{


	private $ci;

	private $mailerConfig = array();

	private $mailer;

	private $debug = false;

	private $adminCopy = false;


	/**
	 * @var NotificationInterface
	 */
	private $notification;

	public function __construct(NotificationInterface $notification)
	{
		$this->ci           = & get_instance();
		$this->notification = $notification;

		$this->ci->load->library('e_mail');
		$this->mailer = $this->ci->e_mail;

		$this->mailerConfig = $this->ci->config->item('mailer');
	}

	public function send()
	{
		$subject   = $this->notification->getSubject();
		$menHTML   = $this->notification->messageBody();
		$menTXT    = trim(strip_tags($menHTML));
		$sendEmail = $this->notification->getFromEmail();
		$sendName  = $this->notification->getFromName();

		/** @var boolean $ret */
		$ret = false;

		/**
		 * if debugging overwrite
		 */
		if ($this->getPrintBody())
		{
			$users = $this->notification->getUsers();

			echo $this->parseMessageBody($menHTML, $users[0]);
			exit;
		}
		else if ($this->getDebug())
		{
			$subject   = '[debug] ' . $subject;
			$user      = array(
				'nome'  => 'degugger',
				'email' => $this->ci->config->item('email_debug')
			);
			$sendEmail = $this->ci->config->item('email_debug');

			$ret       = $this->mailer->envia(
				$user['email'],
				$user['nome'],
				$subject,
				$this->parseMessageBody($menHTML, $user),
				$this->parseMessageBody($menTXT, $user),
				$sendEmail,
				$sendName
			);
		}
		else
		{

			foreach ($this->notification->getUsers() as $user)
			{
				$parsedHtml = $this->parseMessageBody($menHTML, $user);
				$parsedText = $this->parseMessageBody($menTXT, $user);

				$ret = $this->mailer->envia(
					$user['email'],
					$user['nome'],
					$subject,
					$parsedHtml,
					$parsedText,
					$sendEmail,
					$sendName
				);

				if ($this->adminCopy)
				{
					$this->mailer->envia(
						$sendEmail,
						$sendName,
						'[copy] ' . $subject,
						$parsedHtml,
						$parsedText,
						$sendEmail,
						$sendName
					);
				}
			}
		}

		return $ret;
	}

	/**
	 * replace user data on body
	 * [NOME]
	 * [EMAIL]
	 *
	 * @param $body
	 * @param null $user
	 * @return mixed
	 */
	private function parseMessageBody($body, $user = null)
	{
		if (isset($user['nome']))
		{
			$body = str_replace('[NOME]', $user['nome'], $body);
		}

		if (isset($user['email']))
		{
			$body = str_replace('[EMAIL]', $user['email'], $body);
		}

		return $body;
	}

	/**
	 * @param boolean $debug
	 */
	public function setDebug($debug = true)
	{
		$this->debug = $debug;
	}

	/**
	 * @return boolean
	 */
	public function getDebug()
	{
		return $this->debug;
	}

	/**
	 * Get printBody config
	 * @see config/myConfig.php
	 * @return boolean
	 */
	public function getPrintBody()
	{
		return $this->mailerConfig['print_body'];
	}

	/**
	 * send copy to $this->config->item('email1')
	 */
	public function adminCopy()
	{
		$this->adminCopy = true;
	}
}