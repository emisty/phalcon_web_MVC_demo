<?php


error_reporting(E_ALL);
//  phpinfo();exit();

use Phalcon\Mvc\Application;
use Phalcon\Config\Adapter\Ini as ConfigIni;

try {
    //设置时区
    date_default_timezone_set('PRC');

    define('BASE_DIR', dirname(__DIR__));
    define('APP_DIR', BASE_DIR . '/app');
    define('PUB_DIR', BASE_DIR . '/public');
	define('APP_PATH', realpath('..') . '/');
	define('BASE_URL','http://----/'); //所有相关配置 到config.ini
	define('HOUTAI_URL','http://------/');

    //读取配置文件
	$config = new ConfigIni(APP_PATH . 'app/config/config.ini');
	require APP_PATH . 'app/config/services.php';
	require APP_PATH . 'app/config/loader.php';

    //导入依赖库
    // require APP_PATH . 'vendor/autoload.php';


	$application = new Application();

    $application->setDI($di);
    //分模块配置文件echo "done";exit();
	$application->registerModules(array(
	    'compute' => array(
	        'className' => 'Compute\Bootstrap',
	        'path' =>'../app/modules/Compute/Bootstrap.php'
	    ),
	   	'api' => array(
	        'className' => 'Api\Bootstrap',
	        'path' =>'../app/modules/Api/Bootstrap.php'
	    ),
	    'admin' => array(
	        'className' => 'Admin\Bootstrap',
	        'path' =>'../app/modules/Admin/Bootstrap.php'
	    ),
	    'mobile' => array(
	        'className' => 'Mobile\Bootstrap',
	        'path' => '../app/modules/Mobile/Bootstrap.php'
	    )
	));

	echo $application->handle()->getContent();//这里去route
	
} catch (Exception $e){
	echo $e->getMessage();
}

