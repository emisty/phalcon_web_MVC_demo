<?php

return new \Phalcon\Config(array(
	'database' => array(
		'adapter'  => 'Mysql',
		'host'     => '120.25.209.88',
		'username' => 'jzb',
		'password' => '******',
		'dbname'   => 'xxfds',
	),
	'application' => array(
		'controllersDir' => __DIR__ . '/../controllers/',
		'modelsDir'      => __DIR__ . '/../models/',
		'viewsDir'       => __DIR__ . '/../views/',
	)
));
