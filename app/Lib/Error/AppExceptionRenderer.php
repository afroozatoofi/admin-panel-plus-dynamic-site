<?php
/**
 * User: A
 * Date: 2015-03-05
 * Time: 5:51 PM
 */

class AppExceptionRenderer extends ExceptionRenderer
{
//    protected function _getController($exception) {
//        App::uses('HomeFrontController', 'Controller');
//        return new HomeFrontController();
//    }
//    public function notFound($error) {
//        $this->controller->redirect(array('controller' => 'homeFront', 'action' => 'error404'));
//    }

    protected function _cakeError(CakeException $error)
    {
        $this->controller->redirect('/errorx/404/notfound.htm');
    }	

	// Comment By Farhad ;)
//     public function render()
//     {
//         $this->controller->redirect('/errorx/404/notfound.htm');
//     }
    public function error400($error)
    {
        $this->controller->redirect('/errorx/404/notfound.htm');
    }

}