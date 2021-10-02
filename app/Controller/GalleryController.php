<?php
App::uses ( 'AppController', 'Controller' );
class GalleryController extends AppController {
	var $components = array (
			'Image' 
	);
	var $model = 'Gallery';
	public function isAuthorized($user) {
		if (isset ( $user ['forms'] ) && in_array ( 11, $user ['forms'] )) {
			return true;
		}
		return parent::isAuthorized ( $user );
	}
	public function beforeFilter() {
		parent::beforeFilter ();
	}
	public function crud() {
		$this->view = "gallery";
		$this->viewPath = "Dashboard";
		
		$clinic = $this->Auth->user ( 'Clinic' )['name'];
		$this->set ( compact ( 'clinic' ) );
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
				'title',
				'image',
				'isEnabled' 
		);
		
		$cond ['conditions'] = array ();
		parent::setClinicAccess ( $cond ['conditions'] );
		parent::getAllGrid ( $this->model, $fields, $list, $total, $cond );
		
		$list = Set::extract ( '/' . $this->model . '/.', $list );
		foreach ( $list as $K => $N ) {
			$list [$K] ['isEnabled'] = $N ['isEnabled'] == 1 ? 'نمایش' : 'عدم نمایش';
			$list [$K] ['image'] = "<img style='width:100px' src='" . $this->webroot . "img/gallery/thumb/" . $N ['image'] . "' />";
		}
		parent::printGridInfo ( $list, $total );
	}
	public function getAll() {
		$this->autoRender = false;
		echo json_encode ( $this->Gallery->getAll () );
	}
	public function save() {
		$this->autoRender = false;
		if ($this->request->is ( 'post' )) {
			$this->Gallery->create ();
			$e = $this->request->data [$this->model];
			$e ['isEnabled'] = isset ( $e ['isEnabled'] ) ? 1 : 0;
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
			if ($this->Gallery->save ( $e )) {
				echo $this->Gallery->id;
			}
		}
		if (! empty ( $this->Gallery->validationErrors )) {
			parent::applicationException ( $this->Gallery->validationErrors );
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
		
		$target_dir = 'img/gallery/';
		$original_dir = $target_dir . 'original/';
		
		$img = parent::upload ( $original_dir, "image" );
		
		try {
			$this->Image->Thumbfilename = $img;
			
			$this->Image->Thumbwidth = 275;
			$this->Image->Thumbheight = 200;
			$this->Image->Thumblocation = $target_dir . 'thumb/';
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
