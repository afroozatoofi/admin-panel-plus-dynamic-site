<?php
App::uses ( 'AppController', 'Controller' );
class GroupsController extends AppController {
	var $model = 'Group';
	public function isAuthorized($user) {
		if (isset ( $user ['forms'] ) && in_array ( 4, $user ['forms'] )) {
			return true;
		}
		return parent::isAuthorized ( $user );
	}
	public function beforeFilter() {
		parent::beforeFilter ();
	}
	public function crud() {
		$this->view = "group";
		$this->viewPath = "Users";
	}
	public function load($id = null) {
		$this->autoRender = false;
		$Obj = parent::loadById ( $this->model, $id );
		echo json_encode ( $Obj );
	}
	public function view() {
		$this->autoRender = false;
		
		$fields = array (
				'id',
				'name' 
		);
		
		parent::getAllGrid ( $this->model, $fields, $list, $total );
		
		$list = Set::extract ( '/' . $this->model . '/.', $list );
		parent::printGridInfo ( $list, $total );
	}
	public function getAll() {
		$this->autoRender = false;
		echo json_encode ( $this->Group->getAll () );
	}
	public function save() {
		$this->autoRender = false;
		if ($this->request->is ( 'post' )) {
			$this->Group->create ();
			if ($this->Group->save ( $this->request->data )) {
				echo $this->Group->id;
			}
		}
		if (! empty ( $this->Group->validationErrors )) {
			parent::applicationException ( $this->Group->validationErrors );
		}
	}
	public function delete($id = null) {
		$this->autoRender = false;
		parent::deleteById ( $this->model, $id );
	}
}
