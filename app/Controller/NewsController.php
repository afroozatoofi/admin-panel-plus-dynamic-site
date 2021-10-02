<?php
App::uses ( 'AppController', 'Controller' );
class NewsController extends AppController {
	var $model = 'News';
	var $components = array (
			'jdatetime',
			'Image' 
	);
	public function isAuthorized($user) {
		if (isset ( $user ['forms'] ) && in_array ( 12, $user ['forms'] )) {
			return true;
		}
		return parent::isAuthorized ( $user );
	}
	public function beforeFilter() {
		parent::beforeFilter ();
	}
	public function crud() {
		$this->view = "news";
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
				'title',
				'summary',
				'jalaliDate' 
		);
		
		$cond ['conditions'] = array ();
		parent::setClinicAccess ( $cond ['conditions'] );
		parent::getAllGrid ( $this->model, $fields, $list, $total, $cond );
		
		$list = Set::extract ( '/' . $this->model . '/.', $list );
		foreach ( $list as $K => $N ) {
			if (! empty ( $N ['image'] )) {
				$list [$K] ['image'] = "<img style='width:100px' src='" . $this->webroot . "img/news/thumb/" . $N ['image'] . "' />";
			}
		}
		parent::printGridInfo ( $list, $total );
	}
	public function save() {
		$this->autoRender = false;
		if ($this->request->is ( 'post' )) {
			$this->News->create ();
			
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
			$e ['date'] = $this->jdatetime->miladi ( $e ['jalaliDate'], 'Y/m/d H:i:s' );
			
			if ($this->News->save ( $e )) {
				echo $this->News->id;
			}
		}
		if (! empty ( $this->News->validationErrors )) {
			parent::applicationException ( $this->News->validationErrors );
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
		
		$target_dir = 'img/news/';
		$original_dir = $target_dir . 'original/';
		
		$img = parent::upload ( $original_dir, "image" );
		
		try {
			$this->Image->Thumbfilename = $img;
			
			$this->Image->Thumbwidth = 275;
			$this->Image->Thumbheight = 250;
			$this->Image->Thumblocation = $target_dir . 'thumb/';
			$this->Image->Createthumb ( $original_dir . $img, "file" );
			
			$this->Image->Thumbwidth = 500;
			$this->Image->Thumbheight = 454;
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
