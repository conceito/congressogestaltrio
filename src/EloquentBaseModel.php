<?php namespace Src;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Src\Presenters\PresentableInterface;

require_once("EloquentConnection.php");


class EloquentBaseModel extends Model implements PresentableInterface{


	protected static $presenterInstances = null;

	/**
	 * Return a instance of the presenter
	 * @throws \Exception
	 * @return mixed
	 */
	public function present()
	{
		if(! isset($this->presenter) || ! class_exists($this->presenter))
		{
			throw new Exception("Por favor declare o atributo \$this->presenter do seu model.");
		}

		$cls = get_called_class() . $this->id; // late-static-bound class name

		if (!isset(self::$presenterInstances[$cls]))
		{
			self::$presenterInstances[$cls] = new $this->presenter($this);
		}

		return self::$presenterInstances[$cls];
	}

	/**
	 * Alias for present()
	 * @return mixed
	 */
	public function p()
	{
		return $this->present();
	}
}