<?php
App::uses ( 'AppController', 'Controller' );
class BiographyController extends AppController {
	var $model = 'Biography';
	var $components = array (
			'Image' 
	);
	// tabe baraye dastrasi dashtane user be form
	public function isAuthorized($user) {
		if (isset ( $user ['forms'] ) && in_array ( 17, $user ['forms'] )) {
			return true;
		}
		return parent::isAuthorized ( $user );
	}
	public function beforeFilter() {
		parent::beforeFilter ();
	}
	public function crud() {
		$this->view = "biography";
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
				'image',
				'name',
				'detail',
		);
		
		$cond ['conditions'] = array ();
		parent::setClinicAccess ( $cond ['conditions'] );
		parent::getAllGrid ( $this->model, $fields, $list, $total, $cond );
		
		$list = Set::extract ( '/' . $this->model . '/.', $list );
		foreach ( $list as $K => $N ) {
			if (! empty ( $N ['image'] )) {
				$list [$K] ['image'] = "<img style='width:100px' src='" . $this->webroot . "img/bio/thumb/" . $N ['image'] . "' />";
			}
		}
		parent::printGridInfo ( $list, $total );
	}
	public function save() {
		$this->autoRender = false;
		if ($this->request->is ( 'post' )) {
			$this->Biography->create ();
			
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
			
			if ($this->Biography->save ( $e )) {
				echo $this->Biography->id;
			}
		}
		if (! empty ( $this->Biography->validationErrors )) {
			parent::applicationException ( $this->Biography->validationErrors );
		}
	}
	public function delete($id = null) {
		$this->autoRender = false;
		$cond = array ();
		parent::setClinicAccess ( $cond );
		$cond ['id'] = $id;
		parent::deleteAll ( $this->model, $cond );
	}
	function uploadImage() {
		$this->autoRender = false;
		
		$target_dir = 'img/bio/';
		$original_dir = $target_dir . 'original/';
		
		$img = parent::upload ( $original_dir, "image" );
		
		try {
			$this->Image->Thumbfilename = $img;
			
			$this->Image->Thumbwidth = 150;
			$this->Image->Thumbheight = 200;
			$this->Image->Thumblocation = $target_dir . 'thumb/';
			$this->Image->Createthumb ( $original_dir . $img, "file" );
			
			$this->Image->Thumbwidth = 300;
			$this->Image->Thumbheight = 400;
			$this->Image->Thumblocation = $target_dir . 'normal/';
			$this->Image->Createthumb ( $original_dir . $img, "file" );
			
			echo json_encode ( array (
					'name' => $img 
			) );
		} catch ( Exception $e ) {
			$err = array (
					'error' => array (
							'خطا در آپلود تصویر رخ داده است' 
					) 
			);
			parent::applicationException ( $err );
		}
	}
}
