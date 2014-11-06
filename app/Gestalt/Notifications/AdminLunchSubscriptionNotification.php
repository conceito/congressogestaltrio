<?php namespace Gestalt\Notifications;

use Cms\Notifications\AbstractNotification;

class AdminLunchSubscriptionNotification extends AbstractNotification
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



	function __construct($nome = '', $email = '', $tel = '', $pagamento = '')
	{
		parent::__construct();
		$this->nome = $nome;
		$this->email = $email;
		$this->tel = $tel;
		$this->pagamento = $pagamento;
	}

	public function send()
	{
		$this->subject = "Inscrição almoço ({$this->nome})";

		$this->setUsers(array(
			'nome' => $this->config->item('title'),
			'email' => 'almoco@congressogestaltrio.com.br'
		));

		$this->setFrom($this->email, $this->nome);

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


		$body = $this->load->view('email/lunch_subscription', $v, true);

		return $this->composeTemplate($body);
	}
}