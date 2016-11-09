<?php
namespace Compute;

use Phalcon\Loader;
use Phalcon\DiInterface;
use Phalcon\Mvc\View;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Cache\Frontend\Output as OutputFrontend;
use Phalcon\Cache\Backend\Memcache as MemcacheBackend;


/**
 * 将volt 的{{ }} 改为 {[ ]} angularjs冲突
 */
class LiveVolt extends \Phalcon\Mvc\View\Engine\Volt{
    public function getCompiler(){
        if (empty($this->_compiler)){
            $this->_compiler = new LiveVoltCompiler($this->getView());
            $this->_compiler->setOptions($this->getOptions());
            $this->_compiler->setDI($this->getDI());
        }
        return $this->_compiler;
    }
}

class LiveVoltCompiler extends \Phalcon\Mvc\View\Engine\Volt\Compiler{
    protected function _compileSource($source, $something = null){
        $source = str_replace('{{', '<' . '?php $ng = <<<NG' . "\n" . '\x7B\x7B', $source);
        $source = str_replace('}}', '\x7D\x7D' . "\n" . 'NG;' . "\n" . ' echo $ng; ?' . '>', $source);

        $source = str_replace('{[', '{{', $source);
        $source = str_replace(']}', '}}', $source);

        return parent::_compileSource($source, $something);
    }
}


class Bootstrap implements ModuleDefinitionInterface{


    //注册模型自动加载
    public function registerAutoloaders(DiInterface $di=NULL){

        $loader = new Loader();

        $loader->registerNamespaces(array(
            'Compute' => __DIR__ . '/controllers/'
        ));

        $loader->register();
    }

    //注册模块私有服务
    public function registerServices(DiInterface $di=NUll){

        //加載配置文件
        $config = include __DIR__ . "/config/config.php";

        //加載view組件
        $di->set('view', function() use ($config) {

            $view = new View();
            $view->setViewsDir(__DIR__ . '/views/');
            $view->registerEngines(array(
                ".volt" => 'volt'
            ));
            return $view;
        });

        //配置volt模版
        $di->set('volt', function($view, $di) {

            $volt = new VoltEngine($view, $di);
            //volt模版 緩存到
            $volt->setOptions(array(
                "compiledPath" => __DIR__."/../../../cache/cvolt/"
            ));

            $compiler = $volt->getCompiler();
            $compiler->addFunction('is_a', 'is_a');

            return $volt;
        }, true);

        // Set the views cache service
        $di->set('viewCache', function () {

            // Cache data for one day by default
            $frontCache = new OutputFrontend(
                array(
                    "lifetime" => 86400
                )
            );

            // Memcached connection settings
            $cache = new MemcacheBackend(
                $frontCache,
                array(
                    "host" => "localhost",
                    "port" => "11211"
                )
            );

            return $cache;
        });
    }

}

