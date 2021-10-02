<?php
App::uses ( 'Controller', 'Controller' );
class ApiController extends Controller {
	function beforeRender() {
		parent::beforeFilter ();
	}
	function location() {
		$this->set ( "title", "نقشه" );
	}
}
