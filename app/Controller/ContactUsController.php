<?php
App::uses ( 'AppController', 'Controller' );
class ContactUsController extends AppController {
	var $model = 'ContactUs';
	public function isAuthorized($user) {
		if (isset ( $user ['forms'] ) && in_array ( 14, $user ['forms'] )) {
			return true;
		}
		return parent::isAuthorized ( $user );
	}
	public function beforeFilter() {
		$this->loadModel ( $this->model );
		parent::beforeFilter ();
	}
	public function form() {
		$this->view = "contact";
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
		
		$Obj = $this->ContactUs->find ( 'first', $options );
		echo json_encode ( $Obj );
	}
	public function save() {
		$this->autoRender = false;
		if ($this->request->is ( 'post' )) {
			$this->ContactUs->create ();
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
			
			if ($this->ContactUs->save ( $e )) {
				$CU = $this->ContactUs->id;
				$tels = $e ['telephone'];
				if (! empty ( $tels )) {
					$this->loadModel ( 'ContactTel' );
					if (! empty ( $e ['id'] )) {
						$this->ContactTel->deleteAll ( array (
								'contact_id' => $e ['id'] 
						), false );
					}
					
					$tels = split ( ";%&", $tels );
					foreach ( $tels as $tel ) {
						$this->ContactTel->create ();
						$this->ContactTel->save ( array (
								'contact_id' => $CU,
								'telephone' => $tel 
						) );
					}
				}
			}
		}
		if (! empty ( $this->ContactUs->validationErrors )) {
			parent::applicationException ( $this->ContactUs->validationErrors );
		}
		if (! empty ( $this->ContactTel->validationErrors )) {
			parent::applicationException ( $this->ContactTel->validationErrors );
		}
	}
}
