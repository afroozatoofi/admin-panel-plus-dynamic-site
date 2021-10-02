<?php
App::uses ( 'AppController', 'Controller' );
App::uses ( 'ImageHelper', 'Controller/Helper' );
class ClinicsController extends AppController {
	var $components = array (
			'Image' 
	);
	var $model = 'Clinic';
	public function isAuthorized($user) {
		if (isset ( $user ['forms'] ) && in_array ( 7, $user ['forms'] )) {
			return true;
		}
		return parent::isAuthorized ( $user ); 
	}
	public function beforeFilter() {
		$this->loadModel ( $this->model );
		parent::beforeFilter ();
	}
	public function crud() {
		$this->view = "clinic";
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
				'image',
				'footerImage',
				'name',
				'englishName',
				'activity',
				'isEnabled' 
		);
		
		parent::getAllGrid ( $this->model, $fields, $list, $total );
		$list = Set::extract ( '/' . $this->model . '/.', $list );
		$ml = 200;
		foreach ( $list as $K => $N ) {
			$list [$K] ['isEnabled'] = $N ['isEnabled'] == 1 ? 'فعال' : 'غیرفعال';
			if (! empty ( $N ['image'] )) {
				$list [$K] ['image'] = "<img style='width:150px' src='" . $this->webroot . "img/clinic/" . $N ['image'] . "' />";
			}
			if (! empty ( $N ['footerImage'] )) {
				$list [$K] ['footerImage'] = "<img style='width:150px' src='" . $this->webroot . "img/clinic/footer/" . $N ['footerImage'] . "' />";
			}
			$activity = mb_substr ( $N ['activity'], 0, $ml );
			if (mb_strlen ( $N ['activity'] ) > $ml) {
				$activity .= "...";
			}
			$list [$K] ['activity'] = $activity;
		}
		parent::printGridInfo ( $list, $total );
	}
	public function save() {
		$this->autoRender = false;
		if ($this->request->is ( 'post' )) {
			$this->request->data [$this->model] ['isEnabled'] = isset ( $this->request->data [$this->model] ['isEnabled'] ) ? 1 : 0;
			$this->Clinic->create ();
			$e = $this->request->data [$this->model];
			if ($this->Clinic->save ( $e )) {
				echo $this->Clinic->id;
			}
		}
		if (! empty ( $this->Clinic->validationErrors )) {
			parent::applicationException ( $this->Clinic->validationErrors );
		}
	}
	function uploadImage() {
		$this->autoRender = false;
		
		$target_dir = 'img/clinic/';
		
		$thumb = parent::upload ( $target_dir, "image" );
		
		echo json_encode ( array (
				'name' => $thumb 
		) );
	}
	function uploadfImage() {
		$this->autoRender = false;
	
		$target_dir = 'img/clinic/footer/';
	
		$thumb = parent::upload ( $target_dir, "fimage" );
	
		echo json_encode ( array (
				'name' => $thumb
		) );
	}
	function getAllOther() {
		$this->autoRender = false;
		echo json_encode ( $this->Clinic->getAllOther () );
	}
}
