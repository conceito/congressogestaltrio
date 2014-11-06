<?php namespace Src\Core\Filters;

/**
 * Class BaseFilter
 *
 * <code>
 *
 * // on controller
 * $this->filter('Src\Core\Filters\AuthFilter');
 *
 * // option
 * $this->filter('Src\Core\Filters\AuthFilter', array('method1', 'method2'));
 *
 * </code>
 *
 * @package Src\Core\Filters
 */
abstract class BaseFilter implements FilterInterface{

} 