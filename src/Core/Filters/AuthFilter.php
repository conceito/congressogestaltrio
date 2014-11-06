<?php namespace Src\Core\Filters;


use Auth;
use Exception;

class AuthFilter extends BaseFilter{

	/**
	 * Run the filter
	 *
	 * @param string $controller
	 * @param string $method
	 * @param array $fullUri
	 * @throws \Exception
	 * @return mixed
	 */
	public function filter($controller = '', $method = '', $fullUri = array())
	{
		if(Auth::guest())
		{
			d("AuthFilter: Você não tem permissão para acessar esta página.");
		}
	}
}