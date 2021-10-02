<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses ( 'Controller', 'Controller' );
App::uses ( 'FrontHelper', 'View/Helper' );

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppFrontController extends Controller {
	public $components = array (
			'Session' 
	);
	function beforeFilter() {
	}
	function beforeRender() {
		$this->layout = 'front-default';
		$this->viewPath = "Front";
		$cdnroot = $this->webroot;
		$cname = Router::getParam ( "name" );
		$this->set ( compact ( 'cdnroot', 'cname' ) );
	}
	function setTitle($title) {
		$this->set ( compact ( 'title' ) );
	}
	function setDefaultLayoutParams($clinicEnglishName) {
		$this->loadModel ( 'Clinic' );
		$this->Clinic->hasOne = array (
				"Contact" => array (
						'className' => 'ContactUs',
						'foreignKey' => 'clinic_id' 
				) 
		);
		$this->Clinic->hasMany = array (
				"Slider" => array (
						'className' => 'Slider',
						'foreignKey' => 'clinic_id',
						'conditions' => array (
								'Slider.isEnabled' => 1 
						) 
				) 
		);
		$this->Clinic->recursive = 2;
		$cc = $this->Clinic->find ( 'first', array (
				'conditions' => array (
						'englishName' => $clinicEnglishName,
						'isEnabled' => 1 
				) 
		) );
		if (empty ( $cc )) {
			throw new NotFoundException ();
		}
		$clinic = $cc ['Clinic'];
		$contact = $cc ['Contact'];
		$sliders = $cc ['Slider'];
		$this->set ( compact ( 'clinic', 'contact', 'sliders' ) );
		return $clinic;
	}
	function setIndexParams($page = 1, $count = 8) {
		$this->loadModel ( 'Clinic' );
		$this->loadModel ( 'User' );
		$clinics = $this->Clinic->find ( 'all', array (
				'conditions' => array (
						'isEnabled' => 1 
				),
				'offset' => ($page - 1) * $count,
				'limit' => $count 
		) );
		$clinics = Set::extract ( '/Clinic/.', $clinics );
		
		$this->Clinic->hasOne = array (
				"About" => array (
						'className' => 'AboutUs',
						'foreignKey' => 'clinic_id' 
				),
				"Contact" => array (
						'className' => 'ContactUs',
						'foreignKey' => 'clinic_id' 
				) 
		);
		$this->User->recursive = 2;
		$cc = $this->User->find ( 'first', array (
				'conditions' => array (
						'isAdmin' => '1' 
				) 
		) );
		$res = $cc ['Clinic'] ['About'];
		if ($res != null) {
			$about = $res ['summary'];
		}
		$contact = $cc ['Clinic'] ['Contact'];
		$index = true;
		$this->set ( compact ( 'clinics', 'about', 'contact', 'index' ) );
	}
	function setHomeParams($clinicId) {
		$this->loadModel ( 'Clinic' );
		$this->Clinic->hasOne = array (
				"About" => array (
						'className' => 'AboutUs',
						'foreignKey' => 'clinic_id' 
				) 
		);
		$this->Clinic->hasMany = array (
				"Gallery" => array (
						'className' => 'Gallery',
						'foreignKey' => 'clinic_id',
						'conditions' => array (
								'Gallery.isEnabled' => 1 
						),
						'order' => 'id desc',
						'limit' => 8 
				),
				"News" => array (
						'className' => 'News',
						'foreignKey' => 'clinic_id',
						'order' => 'id desc',
						'limit' => 8 
				),
				"Section" => array (
						'className' => 'Section',
						'foreignKey' => 'clinic_id',
						'order' => 'index asc' 
				) 
		);
		$cc = $this->Clinic->find ( 'first', array (
				'conditions' => array (
						'Clinic.id' => $clinicId,
						'isEnabled' => 1 
				) 
		) );
		$res = $cc ['About'];
		if ($res != null) {
			$about = $res ['summary'];
		}
		$gallery = $cc ['Gallery'];
		$news = $cc ['News'];
		$sections = $cc ['Section'];
		$this->set ( compact ( 'about', 'gallery', 'news', 'sections' ) );
	}
	function setAboutParam($clinicId) {
		$this->Clinic->hasOne = array (
				"About" => array (
						'className' => 'AboutUs',
						'foreignKey' => 'clinic_id' 
				) 
		);
		$this->Clinic->hasMany = array ();
		$cc = $this->Clinic->find ( 'first', array (
				'conditions' => array (
						'Clinic.id' => $clinicId,
						'isEnabled' => 1 
				) 
		) );
		$res = $cc ['About'];
		if ($res != null) {
			$about = str_replace ( "\r\n", "<br />", $res ['description'] );
		}
		$this->set ( compact ( 'about' ) );
	}
	function setGalleryParam($clinicId, $page, $count) {
		$this->loadModel ( 'Gallery' );
		$conditions = array (
				'limit' => $count,
				'offset' => ($page - 1) * $count,
				'conditions' => array (
						'clinic_id' => $clinicId,
						'isEnabled' => 1 
				),
				'order' => 'id desc' 
		);
		$list = $this->Gallery->find ( 'all', $conditions );
		$gallery = Set::extract ( '/Gallery/.', $list );
		
		unset ( $conditions ['limit'] );
		$total = $this->Gallery->find ( 'count', $conditions );
		$this->setButtonStatus ( $page, $count, $total );
		$this->set ( compact ( 'gallery' ) );
	}
	function setNewsParam($clinicId, $page, $count) {
		$this->loadModel ( 'News' );
		$conditions = array (
				'limit' => $count,
				'offset' => ($page - 1) * $count,
				'conditions' => array (
						'clinic_id' => $clinicId 
				),
				'order' => 'id desc' 
		);
		$list = $this->News->find ( 'all', $conditions );
		$news = Set::extract ( '/News/.', $list );
		unset ( $conditions ['limit'] );
		$total = $this->News->find ( 'count', $conditions );
		$this->setButtonStatus ( $page, $count, $total );
		$this->set ( compact ( 'news' ) );
	}
	function setBioParam($clinicId) {
		$this->loadModel ( 'Biography' );
		$conditions = array (
				'conditions' => array (
						'clinic_id' => $clinicId 
				),
				'order' => 'name asc' 
		);
		$list = $this->Biography->find ( 'all', $conditions );
		$bio = Set::extract ( '/Biography/.', $list );
		$this->set ( compact ( 'bio' ) );
	}
	function getPage() {
		if (isset ( $this->request->params ['page'] )) {
			$page = $this->request->params ['page'];
		} else {
			$page = 1;
		}
		return $page;
	}
	function setButtonStatus($page, $count, $total) {
		$prev = $page <= 1 ? false : true;
		$next = $page * $count < $total ? true : false;
		$this->set ( compact ( 'page', 'next', 'prev' ) );
	}
	function getClinicName($error = true) {
		$domainName = $_SERVER ['HTTP_HOST'];
		$clinic = mb_substr ( $domainName, 0, mb_strpos ( $domainName, '.' ) );
		if ($error && empty ( $clinic )) {
			throw new NotFoundException ();
		}
		return $clinic;
	}
}
