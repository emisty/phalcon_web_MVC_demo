<?php

namespace Plugins;

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * SecurityPlugin
 * 允许访问列表 指定权限访问
 * @author [jzhbiao]
 */
class Security extends Plugin{

	/**
	 * [getAcl 返回一个允许列表]
	 */
	public function getAcl(){

		if (!isset($this->persistent->acl)) {

			$acl = new AclList();
			$acl->setDefaultAction(Acl::DENY);

			//建立规则
			$roles = array(
				'users'  => new Role('Users'),
				'guests' => new Role('Guests')
			);
			foreach ($roles as $role) {
				$acl->addRole($role);
			}

			//私有权限资源
			$privateResources = array(
				'companies'    => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
				'products'     => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
				'producttypes' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
				'invoices'     => array('index', 'profile')
			);
			foreach ($privateResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//公有权限资源
			$publicResources = array(
				'index'      => array('index','test'),
				'user'		 => array('index','login','register','reg','ceshi'),
				'errors'     => array('show404', 'show500'),
				'register'   => array('index', 'register', 'start', 'end'),
				'session'    => array('index', 'register', 'login','start', 'isSecurimage','creatSecurImage'),
				'contact'    => array('index', 'send'),
				'smscode'    => array('index', 'send'),
				'admin'      => array('index','exam'),
				'login'      => array('index','test'),
			);
			foreach ($publicResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//授权 users|guests 访问共有权限
			foreach ($roles as $role) {
				foreach ($publicResources as $resource => $actions) {
					foreach ($actions as $action){
						$acl->allow($role->getName(), $resource, $action);
					}
				}
			}

			//授权 users 访问私有权限
			foreach ($privateResources as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('Users', $resource, $action);
				}
			}

			//将访问列表存入session, APC would be useful here too
			$this->persistent->acl = $acl;
		}

		return $this->persistent->acl;
	}

	/**
	 * [beforeDispatch 在任何action之前执行]
	 * @param   Event      $event      [description]
	 * @param   Dispatcher $dispatcher [description]
	 * @return  [type]                 [description]
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher){

		$auth = $this->session->get('auth');
		if (!$auth){
			$role = 'Guests';
		} else {
			$role = 'Users';
		}

		$controller = $dispatcher->getControllerName();
		$action     = $dispatcher->getActionName();

		$acl = $this->getAcl();

		$allowed = $acl->isAllowed($role, $controller, $action);

		if ($allowed != Acl::ALLOW){
            //以下判断是必要的 否则跳转循环
			if($dispatcher->getControllerName() != 'errors') {
			 	$dispatcher->forward(array(
					'controller' => 'errors',
				 	'action'     => 'show401'
				));
				return false;
			}

		}
		return;
	}
}
