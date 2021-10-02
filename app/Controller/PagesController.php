<?php
App::uses ( 'AppController', 'Controller' );
class PagesController extends AppController {
	public $uses = array ();
	public function home() {
		$title = 'داشبورد من';
		$user = $this->Auth->user ();
		$this->layout = 'home';
		
		$displayName = $user ['name'] . " " . $user ['family'];
		$username = $user ['username'];
		
		$resources = $user ['resources'];
		$this->set ( compact ( 'title', 'displayName', 'resources', 'username' ) );
	}
	public function impl() {
	}
}
