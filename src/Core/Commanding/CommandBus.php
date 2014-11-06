<?php namespace Src\Core\Commanding;

use InvalidArgumentException;

/**
 * Class CommandBus
 * @package Src\Commanding
 *
 * HOW TO:
 *
 * $this->bus = CommandBus::make();
 *
 * try
	{
		$returned = $this->bus
		->before(array('\Src\Commands\DecorateBeforeCommandHandler'))
		->after(array('\Src\Commands\DecorateAfterCommandHandler'))
		->execute(new \Src\Commands\DoSomethingCommand([params]));
	} catch(Exception $e)
	{
		$e->getMessage();
	}
 *
 */
class CommandBus
{


	/**
	 * @var CommandTranslator
	 */
	private $translator;

	/**
	 * @var array
	 */
	private $before = array();

	/**
	 * @var array
	 */
	private $after = array();

	function __construct(CommandTranslator $translator)
	{

		$this->translator = $translator;
	}

	/**
	 * @return CommandBus
	 */
	public static function make()
	{
		return new self(new CommandTranslator());
	}

	/**
	 * @param $command
	 * @return mixed
	 */
	public function execute($command)
	{
		$handlerName = $this->translator->toCommandHandler($command);

		$command = $this->executeDecorators($command, 'before');

		$handler = new $handlerName;

		$command = $handler->handle($command);

		return $this->executeDecorators($command, 'after');

	}

	/**
	 * Set decorators to run before execute command
	 *
	 * @param array $decorators
	 * @return $this
	 */
	public function before($decorators = array())
	{
		$this->before = array_merge($this->before, $decorators);

		return $this;
	}


	/**
	 * Set decorators to run after execute command
	 *
	 * @param array $decorators
	 * @return $this
	 */
	public function after($decorators = array())
	{
		$this->after = array_merge($this->after, $decorators);

		return $this;
	}

	/**
	 * Execute decorators
	 *
	 * @param $command
	 * @param string $decoratorSide
	 * @return mixed
	 * @throws \InvalidArgumentException
	 */
	private function executeDecorators($command, $decoratorSide = '')
	{
		$commandState = $command;

		foreach ($this->$decoratorSide as $className)
		{
			if(! class_exists($className))
			{
				throw new InvalidArgumentException("The decorate class [$className] does not exists.");
			}

			$instance = new $className;

			if (! $instance instanceof CommandHandler)
			{
				$message = 'The class to decorate must be an implementation of Src\Commanding\CommandHandler';

				throw new InvalidArgumentException($message);
			}

			// execute
			$commandState = $instance->handle($commandState);
		}

		return $commandState;
	}
}