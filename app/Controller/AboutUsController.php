<?php
App::uses ( 'AppController', 'Controller' );
class AboutUsController extends AppController {
	var $model = 'AboutUs';
	public function isAuthorized($user) {
		if (isset ( $user ['forms'] ) && in_array ( 13, $user ['forms'] )) {
			return true;
		}
		return parent::isAuthorized ( $user );
	}
	public function beforeFilter() {
		$this->loadModel ( $this->model );
		parent::beforeFilter ();
	}
	public function form() {
		$this->view = "about";
		$this->viewPath = "Dashboard";
		
		$clinic = $this->Auth->user ( 'Clinic' )['name'];
		$this->set ( compact ( 'clinic' ) );
	}
	public function load($id = null) {
		$this->autoRender = false;
		$options = array (
				'conditions' => array (
						$this->model . '.clinic_id' => $this->Auth->user ( 'clinic_id' ) 
				) 
		);
		
		$Obj = $this->AboutUs->find ( 'first', $options );
		echo json_encode ( $Obj );
	}
	public function save() {
		$this->autoRender = false;
		if ($this->request->is ( 'post' )) {
			$this->AboutUs->create ();
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
			if ($this->AboutUs->save ( $e )) {
				echo $this->AboutUs->id;
			}
		}
		if (! empty ( $this->AboutUs->validationErrors )) {
			parent::applicationException ( $this->AboutUs->validationErrors );
		}
	}
}
