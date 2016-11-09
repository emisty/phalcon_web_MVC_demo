<?php


// 路径
// $loadera = new \Phalcon\Loader();
// $loadera->registerDirs(
// 	array(
// 		APP_PATH . $config->application->libraryDirWechat,
// 		// APP_PATH . $config->application->pluginsDir,
// 		// APP_PATH . $config->application->modelsDir,
// 	)
// )->register();


//命名空间
$loader = new \Phalcon\Loader();
$loader->registerNamespaces(array(
	'Plugins' => APP_PATH . $config->application->pluginsDir,
	'Library' => APP_PATH . $config->application->libraryDir,
	'Models'  => APP_PATH . $config->application->modelsDir,
	'Api'  => APP_PATH . $config->application->apiDir,
));
$loader->register();
