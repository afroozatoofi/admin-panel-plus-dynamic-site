<?php
App::uses ( 'AppController', 'Controller' );
class ResourcesController extends AppController {
	var $model = 'Resource';
	public function isAuthorized($user) {
		if (isset ( $user ['forms'] ) && in_array ( 5, $user ['forms'] )) {
			return true;
		}
		return parent::isAuthorized ( $user );
	}
	public function beforeFilter() {
		parent::beforeFilter ();
	}
	public function crud() {
		$this->view = "resource";
		$this->viewPath = "BaseInfo";
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
				'name',
				'url',
				'englishName',
				'class' 
		);
		parent::getAllGrid ( $this->model, $fields, $list, $total );
		$list = Set::extract ( '/' . $this->model . '/.', $list );
		parent::printGridInfo ( $list, $total );
	}
	public function getAll() {
		$this->autoRender = false;
		
		$conditions = array (
				'order' => 'id asc',
				'conditions' => array (
						'type' => 1 
				) 
		);
		$list = $this->Resource->find ( 'all', $conditions );
		$list = Set::extract ( '/' . $this->model . '/.', $list );
		echo json_encode ( $list );
	}
	public function maxCode() {
		$this->autoRender = false;
		
		echo parent::getMaxCode ( $this->model );
	}
	public function getAllMenu() {
		$this->autoRender = false;
		
		$conditions = array (
				'order' => 'parent_id asc, id asc',
				'conditions' => array (
						'type' => 2 
				) 
		);
		$list = $this->Resource->find ( 'all', $conditions );
		$list = Set::extract ( '/' . $this->model . '/.', $list );
		echo json_encode ( $list );
	}
	public function save() {
		$this->autoRender = false;
		if ($this->request->is ( 'post' )) {
			$this->Resource->create ();
			if ($this->Resource->save ( $this->request->data )) {
				echo $this->Resource->id;
			}
		}
		if (! empty ( $this->Resource->validationErrors )) {
			parent::applicationException ( $this->Resource->validationErrors );
		}
	}
	public function delete($id = null) {
		$this->autoRender = false;
		parent::deleteById ( $this->model, $id );
	}
}
