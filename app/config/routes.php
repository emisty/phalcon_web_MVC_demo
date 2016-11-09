<?php

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Router\Group as RouterGroup;

$router = new Router();


$router->setDefaultModule("compute");
$router->setDefaultNamespace("Compute");


// $router->add( "/tyrz/user_index.jsp", array(
//     "controller" => 'index',
//     "action" => 'tyrz'
// ));

$router->add( "/tyrz/user_index.jsp", array(
	"namespace"  => 'Compute',
	'module'     => 'compute',
	'controller' => 'index',
	'action' => 'loginCheck',
));


$router->add('/ad', array(
	"namespace"  => 'Admin',
	'module'     => 'admin',
));

$router->add('/ad/:controller', array(
	"namespace"  => 'Admin',
	'module'     => 'admin',
	'controller' => 1,
));

$router->add('/ad/:controller/:action', array(
	"namespace"  => 'Admin',
	'module' => 'admin',
	'controller' => 1,
	'action' => 2,
));

$router->add('/ad/:controller/:action/:params', array(
	"namespace"  => 'Admin',
	'module' => 'admin',
	'controller' => 1,
	'action' => 2,
	'params' => 3
));

$router->add('/m', array(
	"namespace"  => 'Mobile',
	'module'     => 'mobile',
	'controller' => 'index',
));

$router->add('/m/:controller', array(
	"namespace"  => 'Mobile',
	'module'     => 'mobile',
	'controller' => 1,
));

$router->add('/m/:controller/:action', array(
	"namespace"  => 'Mobile',
	'module' => 'mobile',
	'controller' => 1,
	'action' => 2,
));

$router->add('/m/:controller/:action/:params', array(
	"namespace"  => 'Mobile',
	'module' => 'mobile',
	'controller' => 1,
	'action' => 2,
	'params' => 3
));

$router->add('/api', array(
	"namespace"  => 'Api',
	'module'     => 'api',
	'controller' => 'index',
));

$router->add('/api/:controller', array(
	"namespace"  => 'Api',
	'module'     => 'api',
	'controller' => 1,
));

$router->add('/api/:controller/:action', array(
	"namespace"  => 'Api',
	'module' => 'api',
	'controller' => 1,
	'action' => 2,
));

$router->add('/api/:controller/:action/:params', array(
	"namespace"  => 'Api',
	'module' => 'api',
	'controller' => 1,
	'action' => 2,
	'params' => 3
));

$router->add( "/{param:[a-z]+}\.(jpg|png)", array(
    "controller" => 'index',
    "action" => 'test'
));

$router->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);

$router->removeExtraSlashes(TRUE);

$gouche = new RouterGroup(array(
    'controller' => 'gouche',
    'action'     => 'index'
));

//阅读3级url 限定
$router->add(
    "/yuedu/{param:(detail-).*$}",
    array(
        "controller" => 'yuedu',
        "action"     => 'detail'
    )
);

$router->add(
    "/yuedu/{param:(index-).*$}",
    array(
        "controller" => 'yuedu',
        "action"     => 'index'
    )
);

//购车3级url 限定
$router->add(
    "/gouche/{param:(detail-).*$}",
    array(
        "controller" => 'gouche',
        "action"     => 'detail'
    )
);

//组合 /gouche
$gouche->setPrefix('/gouche');
$gouche->add('/([rpbsma]{1}-[0-9]{2})', array(
	'action'     => 'index',
	'rank'       => 1,
));
$gouche->add('/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})', array(
	'action'     => 'index',
	'rank'       => 1,
	'price'      => 2,
));
$gouche->add('/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})', array(
	'action'     => 'index',
	'rank'       => 1,
	'price'      => 2,
	'brand'      => 3,
));
$gouche->add('/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})', array(
	'action'     => 'index',
	'rank'       => 1,
	'price'      => 2,
	'brand'      => 3,
	'series'     => 4,
));
$gouche->add('/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})', array(
	'action'     => 'index',
	'rank'       => 1,
	'price'      => 2,
	'brand'      => 3,
	'series'     => 4,
    'more'       => 5,
));
$gouche->add('/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})/([rpbsma]{1}-[0-9]{2})', array(
	'action'     => 'index',
	'rank'       => 1,
	'price'      => 2,
	'brand'      => 3,
	'series'     => 4,
    'more'       => 5,
    'pagenum'    => 6,
));

$router->mount($gouche);


//pay
$pay = new RouterGroup(array(
    'controller' => 'pay',
));
$pay->setPrefix('/pay');
$pay->add('/:action', array(
	'action' => 1,
));
$pay->add('/:action/:pram1/:pram2', array(
	'action' => 1,
	'pram1'  => 2,
	'pram2'  => 3,
));

$router->mount($pay);

return $router;
