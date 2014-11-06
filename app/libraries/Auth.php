<?php

use Src\Core\Entities\User;

class Auth
{

	protected $ci;

	protected static $user = null;

	function __construct()
	{
		$this->ci = &get_instance();

	}

	/**
	 * Determine if the current user is authenticated.
	 *
	 * @return bool
	 */
	public static function check()
	{
		return !is_null(self::user());
	}

	/**
	 * Determine if the current user is a guest.
	 *
	 * @return bool
	 */
	public static function guest()
	{
		return !self::check();
	}

	/**
	 * Return the ID of logged user
	 *
	 * @return null
	 */
	public function id()
	{
		if (self::user())
		{
			return self::user()->id;
		}

		return null;
	}

	/**
	 * Return if user is pf | pj
	 * @return null
	 */
	public function type()
	{
		if (self::user())
		{
			return self::user()->tipo;
		}

		return null;
	}

	/**
	 * Get current authenticated user
	 *
	 * @return \Illuminate\Support\Collection|null|static
	 */
	public static function user()
	{
		if (self::$user)
		{
			return self::$user;
		}

		$ci = &get_instance();

		// return all data from session
		$user = $ci->phpsess->get(null, 'user');

		if (!$user)
		{
			return self::$user = null;
		}

		self::$user = User::find($user['id']);

		return self::$user;

	}


	/**
	 * Do login by user ID
	 *
	 * @param $id
	 * @return array|bool
	 */
	public static function loginId($id)
	{
		$ci = &get_instance();

		if (!$ci->cms_usuario)
		{
			$ci->load->library('cms_usuario');
		}

		return $ci->cms_usuario->do_login(array('id' => $id));
	}

	/**
	 * Login by user attributes
	 *
	 * @param array $credentials
	 * @return array|bool
	 */
	public static function attempt(array $credentials)
	{
		$ci = &get_instance();

		if (!$ci->cms_usuario)
		{
			$ci->load->library('cms_usuario');
		}

		if (isset($credentials['senha']))
		{
			$ci->load->helper('checkfix');
			$credentials['senha'] = cf_password($credentials['senha']);
		}

		return $ci->cms_usuario->do_login($credentials);
	}

	public static function logout()
	{
		$ci = &get_instance();
		$ci->phpsess->clear(null, 'user');
	}
} 