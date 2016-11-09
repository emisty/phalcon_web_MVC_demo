<?php
namespace Compute;
use Phalcon\Mvc\Controller;
use Detection\MobileDetect;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;


use Models\User;
/**
 * @author [jzbis] <[jzbis@sina.com]>
 */
class ControllerBase extends Controller{
   

    protected function initialize(){

        $this->tag->prependTitle('name | ');

        if($this->session->get('auth')!=NULL){

        }else if($this->cookies->has('permanent')){
            $this->auth->cookiesLogin();
        }

        // $ismobile=new MobileDetect();
        // $deviceType = ($ismobile->isMobile() ? ($ismobile->isTablet() ? 0 : 1) : 2);
        // if($deviceType==1){
        //     $this->response->redirect("m");
        // }

        $this->view->setVar('logged_in', is_array($this->session->get('auth')));
    }
    /**
     * @param  [type] $uri [description]
     * @return [type]      [description]
     */
    protected function forward($uri){
        $uriParts = explode('/', $uri);
        $params   = array_slice($uriParts, 2);
        return $this->dispatcher->forward(
            array(
                'controller' => $uriParts[0],
                'action'     => $uriParts[1],
                'params'     => $params
            )
        );
    }


}

