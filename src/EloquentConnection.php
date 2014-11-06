<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

$CI = & get_instance();
$config = $CI->db;

$capsule = new Capsule;

$capsule->addConnection([
	'driver' => 'mysql',
	'host' => $config->hostname,
	'database' => $config->database,
	'username' => $config->username,
	'password' => $config->password,
	'charset' => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix' => '',
]);


// Set the cache manager instance used by connections... (optionaL).
//use Illuminate\Support\Container;
//use Illuminate\Cache\CacheManager;
//$cache = new CacheManager(new Container);
//$cache->driver('apc');
//$capsule->setCacheManager($cache);

$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->setAsGlobal();
$capsule->bootEloquent();
