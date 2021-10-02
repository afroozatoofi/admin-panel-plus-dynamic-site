<?php
App::uses ( 'AppController', 'Controller' );
class SectionController extends AppController {
	var $model = 'Section';
	var $components = array (
			'jdatetime' 
	);
	public function isAuthorized($user) {
		if (isset ( $user ['forms'] ) && in_array ( 16, $user ['forms'] )) {
			return true;
		}
		return parent::isAuthorized ( $user );
	}
	public function beforeFilter() {
		parent::beforeFilter ();
	}
	public function crud() {
		$this->view = "section";
		$this->viewPath = "Dashboard";
		
		$clinic = $this->Auth->user ( 'Clinic' )['name'];
		$this->set ( compact ( 'clinic' ) );
	}
	public function load($id = null) {
		$this->autoRender = false;
		$cond = array ();
		parent::setClinicAccess ( $cond );
		$Obj = parent::loadById ( $this->model, $id );
		echo json_encode ( $Obj );
	}
	public function view() {
		$this->autoRender = false;
		
		$fields = array (
				'id',
				'index',
				'title',
				'description' 
		);
		
		$cond ['conditions'] = array ();
		parent::setClinicAccess ( $cond ['conditions'] );
		parent::getAllGrid ( $this->model, $fields, $list, $total, $cond );
		
		$list = Set::extract ( '/' . $this->model . '/.', $list );
		parent::printGridInfo ( $list, $total );
	}
	public function save() {
		$this->autoRender = false;
		if ($this->request->is ( 'post' )) {
			$this->Section->create ();
			
			$e = $this->request->data [$this->model];
			$e ['clinic_id'] = $this->Auth->user ( 'clinic_id' );
			if (! empty ( $e ['id'] )) {
				$old = parent::loadById ( $this->model, $e ['id'] );
				if ($old [$this->model] ['clinic_id'] != $e ['clinic_id']) {
					$err = array (
							'error' => array (
									'خطا در اجرای عملیات' 
							) 
					);
					parent::applicationException ( $err );
				}
			}
			
			if ($this->Section->save ( $e )) {
				echo $this->Section->id;
			}
		}
		if (! empty ( $this->Section->validationErrors )) {
			parent::applicationException ( $this->Section->validationErrors );
		}
	}
	public function delete($id = null) {
		$this->autoRender = false;
		$cond = array ();
		parent::setClinicAccess ( $cond );
		$cond ['id'] = $id;
		parent::deleteAll ( $this->model, $cond );
	}
}
