<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Events
 *
 * Based on a simple events system for CodeIgniter.
 *
 * HOW TO:
 * 1) register listeners on: app/config/listeners.php
 * 2) create listeners extending BaseEventListener
 * 3) create Event DTO
 * 4) fire events: Events::trigger('our.event', new OurEvent);
 *
 * @package        CodeIgniter
 * @subpackage    Events
 * @version        1.0
 * @author        Eric Barnes <http://ericlbarnes.com>
 * @author        Dan Horrigan <http://dhorrigan.com>
 * @license        Apache License v2.0
 * @copyright    2010 Dan Horrigan
 *
 */

/**
 * Events Library
 */
class Events
{

	protected $ci;

	/**
	 * @var    array    An array of listeners
	 */
	protected static $_listeners = array();

	function __construct()
	{
		$this->ci = & get_instance();

		$this->registerListeners();

	}


	/**
	 * Register
	 *
	 * Registers a Callback for a given event
	 *
	 * @access    public
	 * @param    string  $event  The name of the event
	 * @param    array  $callback  The callback for the Event
	 * @return    void
	 */
	public static function register($event, $callback)
	{
//		$key                            = get_class($callback[0]) . '::' . $callback[1];
		$key = self::getEventName($callback);
		self::$_listeners[$event][$key] = $callback;
		self::log_message('debug', 'Events::register() - Registered "' . $key . ' with event "' . $event . '"');
	}

	// ------------------------------------------------------------------------

	/**
	 * Trigger
	 *
	 * Triggers an event and returns the results.  The results can be returned
	 * in the following formats:
	 *
	 * 'array'
	 * 'json'
	 * 'serialized'
	 * 'string'
	 *
	 * @access    public
	 * @param    string $event The name of the event
	 * @param    mixed $data Any data that is to be passed to the listener
	 * @param    string $return_type The return type
	 * @return    mixed    The return of the listeners, in the return type
	 */
	public static function trigger($event, $data = '', $return_type = null)
	{
		self::log_message('debug', 'Events::trigger() - Triggering event "' . $event . '"');

		$calls = array();

		/**
		 * run defaults listeners
		 */
		if(isset(self::$_listeners['*']))
		{
			foreach (self::$_listeners['*'] as $listener)
			{
				if (is_callable(array($listener, 'handle')))
				{
					$handler = new $listener;
					$calls[self::getEventName($listener)] = $handler->handle($data);
				}
			}
		}



		/**
		 * run user registered listeners
		 */
		if (self::has_listeners($event))
		{
			foreach (self::$_listeners[$event] as $listener)
			{
				if (is_callable(array($listener, 'handle')))
				{
					$handler = new $listener;
					$calls[self::getEventName($listener)] = $handler->handle($data);
				}
			}
		}

		return self::_format_return($calls, $return_type);
	}

	// ------------------------------------------------------------------------

	/**
	 * Format Return
	 *
	 * Formats the return in the given type
	 *
	 * @access    protected
	 * @param    array    The array of returns
	 * @param    string    The return type
	 * @return    mixed    The formatted return
	 */
	protected static function _format_return(array $calls, $return_type)
	{
		self::log_message('debug', 'Events::_format_return() - Formating calls in type "' . $return_type . '"');

		switch ($return_type)
		{
			case 'json':
				return json_encode($calls);
				break;
			case 'serialized':
				return serialize($calls);
				break;
			case 'string':
				$str = '';
				foreach ($calls as $call)
				{
					$str .= $call;
				}

				return $str;
				break;
			default:
				return new EventsBag($calls);
				break;
		}

	}


	private static function getEventName($callback)
	{
		return str_replace('\\', '.', $callback);
	}

	// ------------------------------------------------------------------------

	/**
	 * Has Listeners
	 *
	 * Checks if the event has listeners
	 *
	 * @access    public
	 * @param    string    The name of the event
	 * @return    bool    Whether the event has listeners
	 */
	public static function has_listeners($event)
	{
		self::log_message('debug', 'Events::has_listeners() - Checking if event "' . $event . '" has listeners.');

		if (isset(self::$_listeners[$event]) AND count(self::$_listeners[$event]) > 0)
		{
			return true;
		}

		return false;
	}

	// ------------------------------------------------------------------------

	/**
	 * Log Message
	 *
	 * Pulled out for unit testing
	 *
	 * @param string $type
	 * @param string $message
	 * @return void
	 */
	public static function log_message($type = 'debug', $message = '')
	{
		if (function_exists('log_message'))
		{
			log_message($type, $message);
		}
	}

	/**
	 * Load config/listeners.php and register all
	 */
	protected function registerListeners()
	{
		$this->ci->config->load('listeners');

		$listeners = $this->ci->config->item('listeners');

		foreach ((array)$listeners as $eventName => $listenerGroup)
		{
			foreach ($listenerGroup as $listener)
			{
				Events::register($eventName, $listener);
			}
		}
	}
}

/**
 * Class EventsBag
 */
class EventsBag
{

	private $events = array();

	function __construct($events)
	{
		$this->events = $events;
	}

	/**
	 * return all events with some value
	 *
	 * @param bool $withNull
	 * @return mixed
	 */
	public function all($withNull = false)
	{
		if($withNull)
		{
			return $this->events;
		}

		$returned = array();

		foreach($this->events as $name => $value)
		{
			if($value)
			{
				$returned[$name] = $value;
			}
		}

		return $returned;
	}

	/**
	 * Returns the value of the full qualified listener name
	 *
	 * @param $name
	 * @return mixed|null
	 */
	public function only($name)
	{
		if(isset($this->events[$name]))
		{
			return $this->events[$name];
		}

		return null;
	}

	/**
	 * Returns the value of first listener that has a piece of the string
	 *
	 * @param $eventName
	 * @return mixed|null
	 */
	public function has($eventName)
	{
		foreach($this->events as $name => $value)
		{
			if(strpos($name, $eventName) !== false)
			{
				return $value;
			}
		}

		return null;
	}

	public function count()
	{
		return count($this->events);
	}

}

/* End of file events.php */
