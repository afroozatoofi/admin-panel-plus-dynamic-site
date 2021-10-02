<?php
App::uses ( 'AppController', 'Controller' );
class ResourcePoliciesController extends AppController {
	var $model = 'ResourcePolicy';
	public function isAuthorized($user) {
		if (isset ( $user ['forms'] ) && in_array ( 6, $user ['forms'] )) {
			return true;
		}
		return parent::isAuthorized ( $user );
	}
	public function crud() {
		$this->view = "resource_policy";
		$this->viewPath = "Users";
	}
	public function getAllGroup() {
		$this->autoRender = false;
		$this->loadModel ( 'Group' );
		echo json_encode ( $this->Group->getAll () );
	}
	public function getAllMenu() {
		$this->autoRender = false;
		$this->loadModel ( 'Resource' );
		echo json_encode ( $this->Resource->getAll () );
	}
	public function getAll($groupId) {
		$this->autoRender = false;
		$conditions = array (
				'conditions' => array (
						'group_id' => $groupId 
				),
				'fields' => array (
						'resource_id' 
				) 
		);
		$Obj = $this->ResourcePolicy->find ( 'all', $conditions );
		$Obj = Set::extract ( '/' . $this->model . '/.', $Obj );
		echo json_encode ( $Obj );
	}
	public function save() {
		$this->autoRender = false;
		if ($this->request->is ( 'post' )) {
			$this->ResourcePolicy->create ();
			$gid = $this->request->data ['ResourcePolicy'] ['group_id'];
			if (! $gid || $gid < 0) {
				$err = array (
						'error' => array (
								'انتخاب گروه کاربری ضروری می باشد' 
						) 
				);
				parent::applicationException ( $err );
			}
			$this->ResourcePolicy->deleteAll ( array (
					'group_id' => $gid 
			), false );
			$Obj = array ();
			if (! empty ( $this->request->data ['ResourcePolicy'] ['resource_id'] )) {
				foreach ( $this->request->data ['ResourcePolicy'] ['resource_id'] as $resource ) {
					$Obj [] = array (
							'resource_id' => $resource,
							'group_id' => $gid 
					);
				}
			}
			if (! empty ( $Obj )) {
				$this->ResourcePolicy->saveAll ( $Obj );
			}
		}
		if (! empty ( $this->ResourcePolicy->validationErrors )) {
			parent::applicationException ( $this->ResourcePolicy->validationErrors );
		}
	}
}
