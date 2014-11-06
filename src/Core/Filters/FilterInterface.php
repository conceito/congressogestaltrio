<?php namespace Src\Core\Filters;


interface FilterInterface {

	/**
	 * Run the filter
	 *
	 * @param string $controller
	 * @param string $method
	 * @param array $fullUri
	 * @return mixed
	 */
	public function filter($controller = '', $method = '', $fullUri = array());

} 