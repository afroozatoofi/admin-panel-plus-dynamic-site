<?php
App::uses ( 'AppController', 'Controller' );
class QueueController extends AppController {
	var $model = 'Queue';
	public function isAuthorized($user) {
		if (isset ( $user ['forms'] ) && in_array ( 10, $user ['forms'] )) {
			return true;
		}
		return parent::isAuthorized ( $user );
	}
	public function beforeFilter() {
		parent::beforeFilter ();
	}
	public function grid() {
		$this->view = "queue";
		$this->viewPath = "Dashboard";
		
		$btns = array ();
		$btns [] = array (
				'click' => 'addPayment();',
				'text' => 'پرداخت',
				'class' => 'btn-info' 
		);
		$btns [] = array (
				'click' => 'removePayment();',
				'text' => 'عدم پرداخت',
				'class' => 'btn-danger' 
		);
		$this->set ( "btns", $btns );
	}
	public function addPayment($id) {
		$this->autoRender = false;
		$this->Queue->belongsTo = array ();
		$cond = array (
				'id' => $id 
		);
		if ($this->Auth->user ( 'isAdmin' ) != 1) {
			$cond ['clinic_id'] = $this->Auth->user ( 'clinic_id' );
		}
		$this->Queue->updateAll ( array (
				$this->model . '.payment' => 1 
		), $cond );
	}
	public function removePayment($id) {
		$this->autoRender = false;
		$this->Queue->belongsTo = array ();
		$cond = array (
				'id' => $id 
		);
		if ($this->Auth->user ( 'isAdmin' ) != 1) {
			$cond ['clinic_id'] = $this->Auth->user ( 'clinic_id' );
		}
		$this->Queue->updateAll ( array (
				$this->model . '.payment' => 0 
		), $cond );
	}
	public function view() {
		$this->autoRender = false;
		
		$fields = array (
				$this->model . '.id',
				$this->model . '.payment',
				'Clinic.name',
				'Section.title',
				$this->model . '.name',
				$this->model . '.age',
				$this->model . '.sex',
				$this->model . '.telephone',
				$this->model . '.registerDate',
				$this->model . '.visitDate',
				$this->model . '.desc',
				$this->model . '.tracking' 
		);
		
		$admin = CakeSession::read ( 'Auth.User.isAdmin' );
		$cond = array ();
		if ($admin != 1) {
			parent::setClinicAccess ( $cond ['conditions'], $this->model );
		}
		parent::getAllGrid ( $this->model, $fields, $list, $total, $cond );
		
		foreach ( $list as $K => $N ) {
			$list [$K] [$this->model] ['sex'] = $N [$this->model] ['sex'] == 1 ? 'مرد' : 'زن';
			$list [$K] [$this->model] ['clinic'] = $N ['Clinic'] ['name'];
			$list [$K] [$this->model] ['section'] = $N ['Section'] ['title'];
			if ($N [$this->model] ['payment'] == 1) {
				$list [$K] [$this->model] ['payment'] = '<img src="' . $this->webroot . 'img/check.png" />';
			} else {
				$list [$K] [$this->model] ['payment'] = '';
			}
		}
		$list = Set::extract ( '/' . $this->model . '/.', $list );
		parent::printGridInfo ( $list, $total );
	}
	public function loadSections() {
		$this->autoRender = false;
		$this->loadModel ( 'Section' );
		
		$section = $this->Section->getAllByClinic ( CakeSession::read ( 'Auth.User.clinic_id' ) );
		echo json_encode ( $section );
	}
}
