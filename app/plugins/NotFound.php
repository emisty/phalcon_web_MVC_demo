<?php
namespace Plugins;

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;

/**
 * NotFoundPlugin
 * 没有找到的路径
 * @author [jzhbiao]
 */
class NotFound extends Plugin{

	/**
	 * [beforeException 在所有方法之前执行]
	 * @param   Event         $event      [description]
	 * @param   MvcDispatcher $dispatcher [description]
	 * @param   Exception     $exception  [description]
	 */
	public function beforeException(Event $event, MvcDispatcher $dispatcher, DispatcherException $exception){
		
		if ($exception instanceof DispatcherException) {
			switch ($exception->getCode()) {
				case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
				case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:    
            	$dispatcher->forward(array(
					'controller' => 'errors',
					'action'     => 'show404'
				));
				return false;			
			}
		}

    	$dispatcher->forward(array(
			'controller' => 'errors',
			'action'     => 'show404'
		));
		return false;
        
	}
}
