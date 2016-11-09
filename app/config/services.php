<?php

use Phalcon\Mvc\View;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaData;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\Model\Metadata\Files as MetaDataAdapter;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Model\Manager as modelManager;
use Phalcon\Cache\Frontend\Output as OutputFrontend;
use Phalcon\Cache\Backend\Memcache as MemcacheBackend;
use Phalcon\Cache\Backend\Memcache;
use Phalcon\Cache\Frontend\Data as FrontendData;
use Phalcon\Cache\Backend\Memcache as BackendMemcache;
use Phalcon\Crypt;
use Phalcon\Logger\Adapter\File as FileAdapter;

// use Redis;
// use RedisCluster;
use Library\Auth\Auth;
use Library\Acl;
use Library\Mail;
use Library\AssetsManager;
use Library\Carts;
use Library\Ismobile;
use Library\Smscode;
use Library\Util;
use Library\Upay;
use Library\Alipay;
use Library\Apix;
use Library\WeChat\WXSDK;
use Library\ApiChexingyi;
use Library\WxpayApi_php_v3;
// use Library\WxpayAPI_php_v3;




use Plugins\NotFound;
use Models\Wx;

$di = new FactoryDefault();

//配置文件
$di->set('config', $config);

//注册分配事件 启用插件
$di->set('dispatcher', function() use ($di) {

	$eventsManager = new EventsManager;

    //是否在允许访问列表 SecurityPlugin
	//$eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin);

	//错误和未知位置 NotFoundPlugin
	// $eventsManager->attach('dispatch:beforeException', new NotFound);

	$dispatcher = new Dispatcher;
	$dispatcher->setEventsManager($eventsManager);
	return $dispatcher;
});

//url 配置
$di->set('url', function() use ($config){
	$url = new UrlProvider();
	$url->setBaseUri($config->application->baseUri);
	return $url;
});




$di->set('db', function() use ($config) {
    $dbclass = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    return new $dbclass(array(
        "host"     => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname"   => $config->database->dbname,
        "charset"  => $config->database->charset
    ));
});

// $di->set('db', function() use ($config) {
//     $dbclass = 'Phalcon\Db\Adapter\Pdo\\' . $config->ERPdb->adapter;
//     return new $dbclass(array(
//         "host"     => $config->ERPdb->host,
//         "username" => $config->ERPdb->username,
//         "password" => $config->ERPdb->password,
//         "dbname"   => $config->ERPdb->dbname,
//         "charset"  => $config->ERPdb->charset
//     ));
// });

// $di->set('db', function() use ($config) {
//     $dbName = "DRIVER={SQL Server};SERVER=192.168.3.201,1433;DATABASE=XxfCar";
//     $dbclass = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
//     return new $db = odbc_connect($dbName, 'sa','xxfxxf');
// });

$di->setShared("redis", function() {
    $redis = new Redis();
    $redis->connect('120.**.**.**', 6379);
//     $redis->select(1);
    // $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);
//     $redis->setOption(Redis::OPT_PREFIX, $_SERVER['HTTP_HOST'].":");
    return $redis;
});

// $di->setShared("redisC", function() {
//     $cluster = new RedisCluster(NULL, ['112.**.32.77:7001', '112.**.32.77:7002', '112.**.32.77:7003', '112.**.32.77:7004', '112.**.32.77:7005','112.**.32.77:7006']);
//     return $redis;
// });


//cookies默认加密
// $di->set('crypt', function() {
//     $crypt = new Crypt();
//     $crypt->setKey('#1d0u$=bp?.ak//k');
//     return $crypt;
// });

//cookies
$di->set('cookies', function() {
    $cookies = new Phalcon\Http\Response\Cookies();
    $cookies->useEncryption(false);
    // $cookies->useEncryption(true);
    return $cookies;
});

//配置volt模版
// $di->set('volt', function($view, $di) {

// 	$volt = new VoltEngine($view, $di);
//     //解析缓存
// 	$volt->setOptions(array(
// 		"compiledPath" => APP_PATH . "cache/volt/"
// 	));

// 	$compiler = $volt->getCompiler();
// 	$compiler->addFunction('is_a', 'is_a');

// 	return $volt;
// }, true);

//使用metadata接口 元数据缓存
$di->set('modelsMetadata', function () use ($config) {
    return new MetaDataAdapter(array(
        'metaDataDir' => APP_PATH . $config->application->cacheDir . 'metaData/'
    ));
});

//模型缓存服务
$di->set('modelsCache', function() {
    //缓存时间一天
    $frontCache = new FrontendData(array(
        "lifetime" => 86400
    ));

    //Memcached 连接设置
    $cache = new BackendMemcache($frontCache, array(
        "host" => "localhost",
        "port" => "11211"
    ));

    return $cache;
});

// 使用sessionm
$di->set('session', function() {
	$session = new SessionAdapter();
	$session->start();
	return $session;
});
// 使用sessionm
$di->set('smssession', function() {
    $session = new SessionAdapter();
    $session->start();
    return $session;
});

// //设置memcache session缓存
// $di->set('session', function() use ($config){
//     $memcache = new Phalcon\Session\Adapter\Memcache(array(
//         'uniqueId'   => 'm1',
//         'host'       => '127.0.0.1',
//         'port'       => 11211,
//         'lifetime'   => $config->time->userLoginLife,            // 1200 20分钟
//         'prefix'     => 'xxf',
//         'persistent' => true
//     ));
//     $memcache->start();
//     return $memcache;
// });

// // //设置memcache session缓存
// $di->set('smssession', function(){
//     $memcache = new Phalcon\Session\Adapter\Memcache(array(
//         'uniqueId'   => 'msms',
//         'host'       => '127.0.0.1',
//         'port'       => 11211,
//          'lifetime'   => 300,            // 300 5分钟
//         'prefix'     => 'xxf',
//         'persistent' => false
//     ));
//     $memcache->start();
//     return $memcache;
// });

//bytes加密算法 CSRF保护
$di->set('security', function(){
    $security = new Phalcon\Security();
    //Set the password hashing factor to 12 rounds
    $security->setWorkFactor(12);
    return $security;
}, true);

//显示提示插件
$di->set('flash', function(){
	return new FlashSession(array(
		'error'   => 'alert alert-danger',
		'success' => 'alert alert-success',
		'notice'  => 'alert alert-info',
	));
});

//使用modelManager接口 PHQL
$di->set('modelsManager', function() {
	return new modelManager();
});


//未触及应用
$di->set('elements', function(){
	return new Elements();
});

//加载路由文件 routes.php
$di->set('router', function () {
    return require __DIR__ . '/routes.php';
});

//日志 追加
$di->set('wechatpay', function () {
    return new FileAdapter(
        APP_PATH."app/logs/wechatpay.log",
        array(
            'mode' => 'a'
        )
    );
});

//车行易 追加
$di->set('chexyi', function () {
    return new FileAdapter(
        APP_PATH."app/logs/che.log",
        array(
            'mode' => 'a'
        )
    );
});

//日志 追加
$di->set('logupay', function () {
    return new FileAdapter(
        APP_PATH."app/logs/upay.log",
        array(
            'mode' => 'a'
        )
    );
});

//bug 测试输出 追加
$di->set('printb', function () {
    return new FileAdapter(
        APP_PATH."app/logs/bug.log",
        array(
            'mode' => 'a'
        )
    );
});

//日志 追加
$di->set('logali', function () {
    return new FileAdapter(
        APP_PATH."app/logs/alipay.log",
        array(
            'mode' => 'a'
        )
    );
});

//日志 追加
$di->set('logaerp', function () {
    return new FileAdapter(
        APP_PATH."app/logs/erp.log",
        array(
            'mode' => 'a'
        )
    );
});

//日志 追加
$di->set('logaudit', function () {
    return new FileAdapter(
        APP_PATH."app/logs/audit.log",
        array(
            'mode' => 'a'
        )
    );
});

//推送新违章日志 追加
$di->set('logSmsPush', function () {
    return new FileAdapter(
        APP_PATH."app/logs/smsPush.log",
        array(
            'mode' => 'a'
        )
    );
});

//推送统计日志 追加
$di->set('logSmsPushAll', function () {
    return new FileAdapter(
        APP_PATH."app/logs/smsPushAll.log",
        array(
            'mode' => 'a'
        )
    );
});

//推送新违章微信日志 追加
$di->set('logSmsPushForWechat', function () {
    return new FileAdapter(
        APP_PATH."app/logs/smsPushForWechat.log",
        array(
            'mode' => 'a'
        )
    );
});

//推送统计微信日志 追加
$di->set('logSmsPushAllForWechat', function () {
    return new FileAdapter(
        APP_PATH."app/logs/smsPushAllForWechat.log",
        array(
            'mode' => 'a'
        )
    );
});

//推送统计微信日志 追加
$di->set('logSmsPushError', function () {
    return new FileAdapter(
        APP_PATH."app/logs/smsPushError.log",
        array(
            'mode' => 'a'
        )
    );
});

//推送统计微信日志 追加
$di->set('logSmsPushAllError', function () {
    return new FileAdapter(
        APP_PATH."app/logs/smsPushAllError.log",
        array(
            'mode' => 'a'
        )
    );
});

//自定义身份认证组件
$di->set('auth', function () {
    return new Auth();
});

//访问列表组件
$di->set('acl', function () {
    return new Acl();
});

//访问购物车
$di->set('carts', function () {
    return new Cart();
});

//短信验证
$di->set('smscode', function () {
    return new Smscode();
});
//邮箱组件 swift
$di->set('mail', function () {
    return new Mail();
});

//公共组件 util
$di->set('util', function () {
    return new Util();
});

//银联组件 upay
$di->set('upay', function () {
    return new Upay();
});

//银联组件 upay
$di->set('alipay', function () {
    return new Alipay();
});


//接口 apix
$di->set('apix', function () {
    return new Apix();
});

$di->set('chexingyi', function () {
    return new ApiChexingyi();
});

//接口 js css 资源管理
$di->set('assetsManager', function () {
    return new AssetsManager();
});

//访问列表组件
$di->set('wxsdk', function () {
//     return new WXSDK();
    return WXSDK::instance();
});

//微信支付
$di->set('WxpayApi', function () {
    //     return new WXSDK();
    return new WxPayApi();
});
    
$di->set('JsApiPay', function () {
    //     return new WXSDK();
    return new JsApiPay();
});

$di->set('WxpayData', function () {
    //     return new WXSDK();
    return new WxPayUnifiedOrder();
});
    




