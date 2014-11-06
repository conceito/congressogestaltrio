<?php namespace Gestalt\Commands;

class LunchSubscriptionCommand
{
	public $nome;
	public $email;
	public $telefone;
	public $pagamento;

	function __construct($nome, $email, $telefone, $pagamento)
	{
		$this->email     = $email;
		$this->nome      = $nome;
		$this->pagamento = $pagamento;
		$this->telefone  = $telefone;
	}

}