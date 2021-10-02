<?php
App::uses ( 'AppController', 'Controller' );
class MessagesController extends AppController {
	var $model = 'Message';
	var $components = array (
			'jdatetime' 
	);
	public function isAuthorized($user) {
		if (isset ( $user ['forms'] ) && in_array ( 9, $user ['forms'] )) {
			return true;
		}
		return parent::isAuthorized ( $user );
	}
	public function beforeFilter() {
		$this->loadModel ( $this->model );
		parent::beforeFilter ();
	}
	public function getAllClinics() {
		$this->autoRender = false;
		echo json_encode ( $this->Message->Sender->getAllOther () );
	}
	public function send() {
		$this->view = "message";
		$this->viewPath = "Dashboard";
		
		$isAdmin = $this->Auth->user ( 'isAdmin' );
		$this->set ( compact ( 'isAdmin' ) );
	}
	public function view($type = 1) {
		$this->autoRender = false;
		
		$fields = array (
				$this->model . '.id',
				$this->model . '.text',
				$this->model . '.receivers',
				$this->model . '.jalaliDate' 
		);
		
		$cond = array ();
		if ($type == 2) { // پیام ارسالی
			$this->Message->recursive = 2;
			$cond ['conditions'] [$this->model . '.sender_id'] = $this->Auth->user ( 'clinic_id' );
			parent::getAllGrid ( $this->model, $fields, $list, $total, $cond );
			foreach ( $list as $K => $R ) {
				$RR = "";
				foreach ( $R ['MessageClinic'] as $Receiver ) {
					$RR .= $Receiver ['Clinic'] ['name'] . ", ";
				}
				
				$list [$K] [$this->model] ['receivers'] = substr ( $RR, 0, - 2 );
			}
		} else if ($type == 1) { // پیام دریافتی
			$MC = "MessageClinic";
			$this->loadModel ( $MC );
			$this->MessageClinic->recursive = 2;
			$this->Message->hasMany = array ();
			$cond ['conditions'] [$MC . '.receiver_id'] = $this->Auth->user ( 'clinic_id' );
			parent::getAllGrid ( $MC, $fields, $list, $total, $cond );
			
			foreach ( $list as $K => $R ) {
				$list [$K] [$this->model] ['receivers'] = $R ['Message'] ['Sender'] ['name'];
			}
		}
		$list = Set::extract ( '/' . $this->model . '/.', $list );
		parent::printGridInfo ( $list, $total );
	}
	public function save() {
		$this->autoRender = false;
		if ($this->request->is ( 'post' )) {
			$this->Message->create ();
			
			$text = $this->request->data ['text'];
			$date = date ( 'Y/m/d H:i:s' );
			$obj = array (
					'text' => trim ( $text ),
					'sender_id' => $this->Auth->user ( 'clinic_id' ),
					'date' => $date,
					'jalaliDate' => $this->jdatetime->shamsi ( strtotime ( $date ), 'Y/m/d H:i:s' ) 
			);
			$clinics = array ();
			if (! empty ( $this->request->data ['clinic_id'] )) {
				$clinics = $this->request->data ['clinic_id'];
			}
			$isAdmin = $this->Auth->user ( 'isAdmin' );
			if (empty ( $clinics ) || empty ( $clinics [0] )) {
				$err = array (
						'error' => array (
								'درمانگاه انتخاب نشده است.' 
						) 
				);
				parent::applicationException ( $err );
			}
			if ($isAdmin != 1 && count ( $clinics ) > 1) {
				$err = array (
						'error' => array (
								'امکان انتخاب بیشتر از یک درمانگاه وجود ندارد' 
						) 
				);
				parent::applicationException ( $err );
			}
			if ($this->Message->save ( $obj )) {
				$mid = $this->Message->id;
				$this->loadModel ( 'MessageClinic' );
				echo $mid;
				foreach ( $clinics as $clinic ) {
					$this->MessageClinic->create ();
					$this->MessageClinic->save ( array (
							'message_id' => $mid,
							'receiver_id' => $clinic 
					) );
				}
			}
		}
		if (! empty ( $this->Message->validationErrors )) {
			parent::applicationException ( $this->Message->validationErrors );
		}
		if (! empty ( $this->MessageClinic->validationErrors )) {
			parent::applicationException ( $this->MessageClinic->validationErrors );
		}
	}
}
