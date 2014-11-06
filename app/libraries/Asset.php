<?php
/**
 * Lib responsável agrupar, ordenar e combinar assets, como: css e js.
 * Faz a injeção de dados no formato JSON na view.
 * Trabalha com conjunto com a lib Carabiner para combinar e minificar.
 *
 * @package Sig
 * @subpackage Assets
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2013 Bruno Barros
 *
 */
//use Illuminate\Support\Facades\HTML as HTML;
//use Illuminate\Support\Facades\URL;
//use Illuminate\Support\Facades\Config;
//use Sig\Assets\Carabiner\Carabiner;

class Asset
{

	/**
	 * All of the instantiated asset containers.
	 *
	 * @var array
	 */
	public static $containers = array();

	/**
	 * Get an asset container instance.
	 *
	 * <code>
	 *        // Get the default asset container
	 *        $container = Asset::container();
	 *
	 *        // Get a named asset container
	 *        $container = Asset::container('footer');
	 * </code>
	 *
	 * @param  string $container
	 * @return Asset_Container
	 */
	public static function container($container = 'default')
	{
		if (!isset(static::$containers[$container]))
		{
			static::$containers[$container] = new Asset_Container($container);
		}

		return static::$containers[$container];
	}

	/**
	 * Magic Method for calling methods on the default container.
	 *
	 * <code>
	 *        // Call the "styles" method on the default container
	 *        echo Asset::styles();
	 *
	 *        // Call the "add" method on the default container
	 *        Asset::add('jquery', 'js/jquery.js');
	 * </code>
	 */
	//    public function __call($method, $parameters)
	//    {
	//        return call_user_func_array(array(static::container(), $method), $parameters);
	//    }

	public static function __callStatic($method, $parameters)
	{
		return call_user_func_array(array(static::container(), $method), $parameters);
	}

}

class Asset_Container
{

	private $ci;

	/**
	 * Caminho relativo para pasta de assets
	 * @var string
	 */
	public $path = 'assets';

	/**
	 * The asset container name.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * All of the registered assets.
	 *
	 * @var array
	 */
	public $assets = array();

	/**
	 * Dados do sistema serão injetados na view no formato JSON
	 * para serem manipulados pelos plugins
	 * @var array
	 */
	private $_json = array();

	private $carabiner = null;
	private $carabinerConfig = array();

	/**
	 * Create a new asset container instance.
	 *
	 * @param  string $name
	 * @return \Asset_Container
	 */
	public function __construct($name)
	{
		$this->ci   = & get_instance();
		$this->name = $name;
		$this->ci->load->library('HtmlBuilder');
		//        $this->carabiner = new Carabiner();
	}

	public function config($configPath = 'carabiner')
	{
		//        if(Config::has($configPath))
		//        {
		//            $this->carabinerConfig = Config::get($configPath);
		//            $this->carabiner->config($this->carabinerConfig);
		//        }
	}

	/**
	 * Add an asset to the container.
	 *
	 * The extension of the asset source will be used to determine the type of
	 * asset being registered (CSS or JavaScript). When using a non-standard
	 * extension, the style/script methods may be used to register assets.
	 *
	 * <code>
	 *        // Add an asset to the container
	 *        Asset::container()->add('jquery', 'js/jquery.js');
	 *
	 *        // Add an asset that has dependencies on other assets
	 *        Asset::add('jquery', 'js/jquery.js', 'jquery-ui');
	 *
	 *        // Add an asset that should have attributes applied to its tags
	 *        Asset::add('jquery', 'js/jquery.js', null, array('defer'));
	 * </code>
	 *
	 * @param  string $name
	 * @param  string $source
	 * @param  array $dependencies
	 * @param  array $attributes
	 * @return Asset_Container
	 */
	public function add($name, $source, $dependencies = array(), $attributes = array())
	{
		$type = (pathinfo($source, PATHINFO_EXTENSION) == 'css') ? 'style' : 'script';

		return $this->$type($name, $source, $dependencies, $attributes);
	}

	/**
	 * Add a CSS file to the registered assets.
	 *
	 * @param  string $name
	 * @param  string $source
	 * @param  array $dependencies
	 * @param  array $attributes
	 * @return Asset_Container
	 */
	public function style($name, $source, $dependencies = array(), $attributes = array())
	{
		if (!array_key_exists('media', $attributes))
		{
			$attributes['media'] = 'all';
		}

		$this->register('style', $name, $source, $dependencies, $attributes);

		return $this;
	}

	/**
	 * Add a JavaScript file to the registered assets.
	 *
	 * @param  string $name
	 * @param  string $source
	 * @param  array $dependencies
	 * @param  array $attributes
	 * @return Asset_Container
	 */
	public function script($name, $source, $dependencies = array(), $attributes = array())
	{
		$this->register('script', $name, $source, $dependencies, $attributes);

		return $this;
	}


	public function setBasePath($path)
	{
		$this->path = $path;
	}

	/**
	 * Returns the full-path for an asset.
	 *
	 * @param  string $source
	 * @return string
	 */
	public function path($source)
	{
		$full = trim(base_url() . app_folder() . $this->path . '/', '/');
		return  $full .'/'. $source;
	}

	/**
	 * Returns the fisic path of a relative path
	 * @param string $source
	 * @return string
	 */
	public function fisicPath($source)
	{
		$full = rtrim(FCPATH . trim($this->path, '/') . '/', '/');

		return $full . '/' . trim($source, '/');
	}

	/**
	 * Add an asset to the array of registered assets.
	 *
	 * @param  string $type
	 * @param  string $name
	 * @param  string $source
	 * @param  array $dependencies
	 * @param  array $attributes
	 * @return void
	 */
	protected function register($type, $name, $source, $dependencies, $attributes)
	{
		$dependencies = (array)$dependencies;

		$attributes = (array)$attributes;

		if (is_file($this->fisicPath($source)))
		{
			$this->assets[$type][$name] = compact('source', 'dependencies', 'attributes');
		}

	}

	/**
	 * Get the links to all of the registered CSS assets.
	 *
	 * @return  string
	 */
	public function styles()
	{
//		if (isset($this->carabinerConfig['dev']) && $this->carabinerConfig['dev'] === false)
//		{
//			$a = explode('|', trim($this->group('style', 'compiled'), '|'));
//
//			foreach ($a as $css)
//			{
//				$this->carabiner->css($css);
//			}
//
//			return $this->carabiner->display('css');
//		}


		return $this->group('style');
	}

	/**
	 * Get the links to all of the registered JavaScript assets.
	 *
	 * @return  string
	 */
	public function scripts()
	{
//		if (isset($this->carabinerConfig['dev']) && $this->carabinerConfig['dev'] === false)
//		{
//			$a = explode('|', trim($this->group('script', 'compiled'), '|'));
//
//			foreach ($a as $js)
//			{
//				$this->carabiner->js($js);
//			}
//
//			return $this->carabiner->display('js');
//		}

		return $this->group('script');
	}

	/**
	 * Get all of the registered assets for a given type / group.
	 *
	 * @param  string $group
	 * @return string
	 */
	protected function group($group, $compiled = '')
	{
		if (!isset($this->assets[$group]) or count($this->assets[$group]) == 0)
		{
			return '';
		}

		$assets = '';

		foreach ($this->arrange($this->assets[$group]) as $name => $data)
		{
			$assets .= $this->asset($group, $name, $compiled);
		}

		return $assets;
	}

	/**
	 * Get the HTML link to a registered asset.
	 *
	 * @param  string $group
	 * @param  string $name
	 * @return string
	 */
	protected function asset($group, $name, $compiled = '')
	{
		if (!isset($this->assets[$group][$name]))
		{
			return '';
		}

		$asset = $this->assets[$group][$name];


		// If the bundle source is not a complete URL, we will go ahead and prepend
		// the bundle's asset path to the source provided with the asset. This will
		// ensure that we attach the correct path to the asset.
		if (filter_var($asset['source'], FILTER_VALIDATE_URL) === false)
		{
			$asset['source'] = $this->path($asset['source']);
		}

		if ($compiled == 'compiled')
		{
			return $asset['source'] . '|';
		}
		else
		{
			return $this->ci->htmlbuilder->$group($asset['source'], $asset['attributes']);
		}

	}

	/**
	 * Sort and retrieve assets based on their dependencies
	 *
	 * @param   array $assets
	 * @return  array
	 */
	protected function arrange($assets)
	{
		list($original, $sorted) = array($assets, array());

		while (count($assets) > 0)
		{
			foreach ($assets as $asset => $value)
			{
				$this->evaluate_asset($asset, $value, $original, $sorted, $assets);
			}
		}

		return $sorted;
	}

	/**
	 * Evaluate an asset and its dependencies.
	 *
	 * @param  string $asset
	 * @param  string $value
	 * @param  array $original
	 * @param  array $sorted
	 * @param  array $assets
	 * @return void
	 */
	protected function evaluate_asset($asset, $value, $original, &$sorted, &$assets)
	{
		// If the asset has no more dependencies, we can add it to the sorted list
		// and remove it from the array of assets. Otherwise, we will not verify
		// the asset's dependencies and determine if they've been sorted.
		if (count($assets[$asset]['dependencies']) == 0)
		{
			$sorted[$asset] = $value;

			unset($assets[$asset]);
		}
		else
		{
			foreach ($assets[$asset]['dependencies'] as $key => $dependency)
			{
				if (!$this->dependency_is_valid($asset, $dependency, $original, $assets))
				{
					unset($assets[$asset]['dependencies'][$key]);

					continue;
				}

				// If the dependency has not yet been added to the sorted list, we can not
				// remove it from this asset's array of dependencies. We'll try again on
				// the next trip through the loop.
				if (!isset($sorted[$dependency]))
				{
					continue;
				}

				unset($assets[$asset]['dependencies'][$key]);
			}
		}
	}

	/**
	 * Verify that an asset's dependency is valid.
	 *
	 * A dependency is considered valid if it exists, is not a circular reference, and is
	 * not a reference to the owning asset itself. If the dependency doesn't exist, no
	 * error or warning will be given. For the other cases, an exception is thrown.
	 *
	 * @param  string $asset
	 * @param  string $dependency
	 * @param  array $original
	 * @param  array $assets
	 * @return bool
	 */
	protected function dependency_is_valid($asset, $dependency, $original, $assets)
	{
		if (!isset($original[$dependency]))
		{
			return false;
		}
		elseif ($dependency === $asset)
		{
			throw new \Exception("Asset [$asset] is dependent on itself.");
		}
		elseif (isset($assets[$dependency]) and in_array($asset, $assets[$dependency]['dependencies']))
		{
			throw new \Exception("Assets [$asset] and [$dependency] have a circular dependency.");
		}

		return true;
	}

	//-------------------------------------------------------------------------

	/**
	 * Combina os arrays e converte em JSON na view.
	 * São variáveis globais para serem usadas via JS nas views.
	 * @param array $array
	 * @param string $namespace
	 */
	public function json($array = null, $namespace = null)
	{
		if ($array == null)
		{
			return false;
		}
		else if (!is_array($array))
		{
			$array = explode(',', $array);
		}

		if ($namespace !== null)
		{
			$array_in = $array;
			unset($array);

			// já existe este índice
			if (isset($this->_json[$namespace]))
			{
				$array[$namespace] = array_merge($this->_json[$namespace], $array_in);
			}
			else
			{
				$array[$namespace] = $array_in;
			}
		}

		$this->_json = array_merge($this->_json, $array);

	}

	//-------------------------------------------------------------------------

	/**
	 * Retorna o código JSON com as variáveis do sistema sobre módulos, cliente,
	 * admins etc
	 * @return string
	 */
	public function echoJson()
	{
		return json_encode($this->_json);
	}

}
