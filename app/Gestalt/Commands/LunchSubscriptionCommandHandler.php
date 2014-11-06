<?php namespace Gestalt\Commands;

use Events;
use Gestalt\Events\UserSignedUpAtLunch;
use Gestalt\Notifications\AdminLunchSubscriptionNotification;
use Gestalt\Notifications\UserLunchSubscriptionNotification;
use Src\Core\Commanding\BaseCommandHandler;

class LunchSubscriptionCommandHandler extends BaseCommandHandler
{

	function __construct()
	{

	}

	/**
	 * @param $cmd
	 */
	public function handle($cmd)
	{
//		dd($cmd);

		$mailerAdmin = new AdminLunchSubscriptionNotification(
			$cmd->nome,
			$cmd->email,
			$cmd->telefone,
			$cmd->pagamento
		);
		$mailerAdmin->send();

		$mailerUser = new UserLunchSubscriptionNotification(
			$cmd->nome,
			$cmd->email,
			$cmd->telefone,
			$cmd->pagamento,
			$this->isParcelado($cmd->pagamento) ? 'parcelado' : 'avista'
		);
		$mailerUser->send();

//		Events::trigger('user.events', new UserSignedUpAtLunch(''));

		return true;
	}

	private function isParcelado($pagamento)
	{
		return (strstr($pagamento, 'parcelado') === false) ? false : true;

	}
}