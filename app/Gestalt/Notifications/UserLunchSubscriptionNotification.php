<?php namespace Gestalt\Notifications;

use Cms\Notifications\AbstractNotification;

class UserLunchSubscriptionNotification extends AbstractNotification
{
	/**
	 * @var string
	 */
	private $nome;
	/**
	 * @var string
	 */
	private $tel;
	/**
	 * @var string
	 */
	private $pagamento;

	private $email;


	private $paymentType  = ''; // 'parcelado' | 'avista'

	function __construct($nome = '', $email = '', $tel = '', $pagamento = '', $paymentType = '')
	{
		parent::__construct();
		$this->nome = $nome;
		$this->email = $email;
		$this->tel = $tel;
		$this->pagamento = $pagamento;
		$this->paymentType = $paymentType;
	}

	public function send()
	{
		$this->subject = "Inscrição almoço ({$this->nome})";

		$this->setUsers(array(
			'nome' => $this->nome,
			'email' => $this->email
		));

		return $this->service->send();
	}


	/**
	 * compose html body with template
	 *
	 * <code>
	 * $body = 'Your body goes here...';
	 * return $this->composeTemplate($body);
	 * </code>
	 *
	 * @return mixed
	 */
	public function messageBody()
	{
		$v['nome'] = $this->nome;
		$v['email'] = $this->email;
		$v['tel'] = $this->tel;
		$v['pagamento'] = $this->pagamento;
		$v['type'] = $this->paymentType;


		$body = $this->load->view('email/user_lunch_subscription', $v, true);

		return $this->composeTemplate($body);
	}
}