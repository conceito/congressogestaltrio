<?php namespace Src\Validation;

/**
 * Class Validator (Singleton)
 *
 * HOW TO:
 * $v = \Src\Validation\ContactFormValidator::getInstance();
 *
 * if (!$v->isValid())
 * {
 *  $v->setError("Campos incorretos."); // optional
 *  // do whatever
 * }
 * else
 * {
 *  $v->setSuccess("Mensagem enviada com sucesso."); // optional
 *  do whatever
 * }
 *
 *
 * @package Src\Validation
 */
abstract class Validator
{

	/**
	 * @var \CI_Controller
	 */
	protected $ci;

	/**
	 * On caller class
	 *
	 * array(
	 * 'field' => 'tel',
	 * 'label' => 'Telefone',
	 * 'rules' => 'callback_external_callbacks[contato_m,custom_rule]'
	 * // or 'rules' => 'callback_external_callbacks[Src\Validation\ContactFormValidator,custom_rule]'
	 * )
	 *
	 * @link https://ellislab.com/codeigniter/user-guide/libraries/form_validation.html#rulereference
	 *
	 * @var array
	 */
	protected $rules = array();

	const ERROR_MSG = 'Campos incorretos.';

	const SUCCESS_MSG = 'Mensagem enviada com sucesso.';

	/**
	 * @var array
	 */
	protected static $instances = array();


	protected function __construct()
	{
		$this->ci = & get_instance();
	}

	/**
	 * @return mixed
	 */
	public static function getInstance()
	{
		$cls = get_called_class(); // late-static-bound class name
		if (!isset(self::$instances[$cls]))
		{
			self::$instances[$cls] = new static;
		}

		return self::$instances[$cls];
	}

	/**
	 * Bootstrap necessary rules
	 */
	private function boot()
	{
		$this->ci->load->library(array('form_validation'));

		$this->setRules();

		$this->setErrorDelimiters();
	}


	/**
	 * @return bool
	 */
	public function isValid()
	{
		$this->boot();

		if ($this->ci->form_validation->run())
		{
			// set default message
			$this->setSuccess(self::SUCCESS_MSG);

			return true;
		}
		else
		{
			// set default message
			$this->setError(self::ERROR_MSG);

			return false;
		}
	}


	/**
	 * Run wrought the caller class rules
	 */
	protected function setRules()
	{
		foreach ($this->rules as $r)
		{
			$this->ci->form_validation->set_rules($r['field'], $r['label'], $r['rules']);
		}
	}

	/**
	 * Set the HTML tags to error messages
	 */
	protected function setErrorDelimiters()
	{
		$this->ci->form_validation->set_error_delimiters('<label class="error">', '</label>');
	}

	/**
	 * Set error message on session
	 * @param string $message
	 */
	public function setError($message = '')
	{
		$this->setMessage('error', $message);
	}

	/**
	 * Set success message on session
	 * @param string $message
	 */
	public function setSuccess($message = '')
	{
		$this->setMessage('success', $message);
	}

	/**
	 * Set messages on session
	 * @param string $type
	 * @param null $message
	 */
	public function setMessage($type = 'info', $message = null)
	{
		\FlashMessage::put($type, $message);

	}

}