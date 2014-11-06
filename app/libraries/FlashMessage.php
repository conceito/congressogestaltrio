<?php

/**
 * Class FlashMessage
 *
 * Provide a static API to set and get flash messages
 *
 * @author Bruno Barros <bruno@brunobarros.com>
 *
 */
class FlashMessage
{


	/**
	 * @var CI_Controller
	 */
	private $ci;

	private static $instance = null;

	/**
	 * @var string
	 */
	private $namespace = '_flashmessage_';

	/**
	 * @var string
	 */
	private $msgTypeKey = 'msg_type';

	/**
	 * @var string
	 */
	private $msgKey = 'msg';

	/**
	 * The type value
	 * @var null
	 */
	public $type = null;

	/**
	 * The message value
	 * @var null
	 */
	public $msg = null;

	/**
	 * The class that match the type
	 * @var string
	 */
	public $class = '';

	/**
	 * Classes to represent message
	 * @var array
	 */
	private $classes = array(
		'info'    => 'alert-info',
		'success' => 'alert-success',
		'error'   => 'alert-danger'
	);


	function __construct()
	{
		$this->ci = & get_instance();
	}

	public function getInstance()
	{
		if (!self::$instance)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}


	/**
	 * Show the message view if has message
	 * Bootstrap 3 template
	 */
	public static function show($closeLink = false)
	{
		if (FlashMessage::has())
		{
			$class = FlashMessage::get()->class;
			echo "<div class=\"alert {$class} \">";

			if($closeLink)
			{
				echo '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span></button>';
			}

			echo FlashMessage::get()->msg;
			echo '</div>';
		}
	}

	/**
	 * Set the type and message
	 *
	 * @param string $type
	 * @param string $message
	 */
	public static function put($type = 'info', $message = '')
	{
		$fm = FlashMessage::getInstance();

		$fm->putMessage($type, $message);
	}

	/**
	 * Set the default type
	 *
	 * @param string $message
	 */
	public static function info($message = '')
	{
		$fm = FlashMessage::getInstance();

		$fm->putMessage('info', $message);
	}

	/**
	 * Set the success message
	 *
	 * @param string $message
	 */
	public static function success($message = '')
	{
		$fm = FlashMessage::getInstance();

		$fm->putMessage('success', $message);
	}

	/**
	 * Set the error message
	 *
	 * @param string $message
	 */
	public static function error($message = '')
	{
		$fm = FlashMessage::getInstance();

		$fm->putMessage('error', $message);
	}

	/**
	 * Get the message object to access the values
	 *
	 * <code>
	 * FlashMessage::get()->type
	 * FlashMessage::get()->msg
	 * FlashMessage::get()->class
	 * </code>
	 *
	 * @return static
	 */
	public static function get()
	{
		$fm = FlashMessage::getInstance();

		$fm->getMessage();

		return $fm;
	}

	/**
	 * Check is has message
	 * @return mixed
	 */
	public static function has()
	{
		$fm = FlashMessage::getInstance();

		return $fm->hasMessage();
	}

	/**
	 * Clear all messages
	 */
	public static function clear()
	{
		$fm = FlashMessage::getInstance();

		$fm->clearMessage();
	}


	/**
	 * ============================
	 *      PRIVATE
	 * ============================
	 */

	/**
	 * Private get message
	 */
	private function getMessage()
	{
		$this->type = ($this->type) ? : $this->ci->phpsess->flashget($this->msgTypeKey);
		$this->msg  = ($this->msg) ? : $this->ci->phpsess->flashget($this->msgKey);

		$this->class = (isset($this->classes[$this->type])) ? $this->classes[$this->type] : null;
	}

	/**
	 * @param string $type
	 * @param string $message
	 */
	private function putMessage($type = 'info', $message = '')
	{
		$this->type  = $type;
		$this->msg   = $message;
		$this->class = $this->classes[$this->type];

		$this->ci->phpsess->flashsave($this->msgTypeKey, $type);
		$this->ci->phpsess->flashsave($this->msgKey, $message);
	}

	/**
	 * @return bool
	 */
	private function hasMessage()
	{
		$msg = ($this->msg) ? : $this->ci->phpsess->flashget($this->msgKey);

		return ($msg) ? true : false;
	}

	/**
	 *
	 */
	private function clearMessage()
	{
		$this->type  = null;
		$this->msg   = null;
		$this->class = null;
		$this->ci->phpsess->flashsave($this->msgTypeKey, null);
		$this->ci->phpsess->flashsave($this->msgKey, null);
	}

}