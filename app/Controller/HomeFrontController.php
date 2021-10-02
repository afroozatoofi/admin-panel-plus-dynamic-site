<?php
App::uses ( 'AppFrontController', 'Controller' );
class HomeFrontController extends AppFrontController {
	public function index() {
		$this->layout = "front-index";
		$this->view = "index";
		$this->setIndexParams();
		parent::setTitle ( "پورتال جامع درمانگاه های استان فارس" );
	}
	public function home() {
		$clinicEnglishName = parent::getClinicName ( false );
		if (empty ( $clinicEnglishName )) {
			$this->index ();
		} else {
			$clinic = parent::setDefaultLayoutParams ( $clinicEnglishName );
			parent::setHomeParams ( $clinic ['id'] );
			parent::setTitle ( $clinic ['name'] . " - صفحه اصلی" );
		}
	}
	public function news() {
		$clinicEnglishName = parent::getClinicName ();
		$clinic = parent::setDefaultLayoutParams ( $clinicEnglishName );
		$page = parent::getPage ();
		parent::setNewsParam ( $clinic ['id'], $page, 6 );
		parent::setTitle ( $clinic ['name'] . " - اخبار" );
	}
	public function biography() {
		$clinicEnglishName = parent::getClinicName ();
		$clinic = parent::setDefaultLayoutParams ( $clinicEnglishName );
		parent::setBioParam ( $clinic ['id'] );
		parent::setTitle ( $clinic ['name'] . " - بیوگرافی" );
	}
	public function gallery() {
		$clinicEnglishName = parent::getClinicName ();
		$clinic = parent::setDefaultLayoutParams ( $clinicEnglishName );
		$page = parent::getPage ();
		parent::setGalleryParam ( $clinic ['id'], $page, 12 );
		parent::setTitle ( $clinic ['name'] . " - گالری تصاویر" );
	}
	public function about() {
		$clinicEnglishName = parent::getClinicName ();
		$clinic = parent::setDefaultLayoutParams ( $clinicEnglishName );
		parent::setAboutParam ( $clinic ['id'] );
		parent::setTitle ( $clinic ['name'] . " - درباره ما" );
	}
	public function queue() {
		$this->Captcha = $this->Components->load ( 'Captcha', array (
				'model' => 'Queue',
				'action' => 'queueCaptcha' 
		) );
		$clinicEnglishName = parent::getClinicName ();
		$clinic = parent::setDefaultLayoutParams ( $clinicEnglishName );
		parent::setTitle ( $clinic ['name'] . " - نوبت دهی" );
		
		$this->set ( compact ( 'clinic' ) );
	}
	function queueCaptcha() {
		$this->autoRender = false;
		$this->layout = 'ajax';
		$this->Captcha = $this->Components->load ( 'Captcha', array (
				'model' => 'Queue',
				'action' => 'queueCaptcha' 
		) );
		$this->Captcha->create ();
	}
	public function loadSections() {
		$this->autoRender = false;
		$this->loadModel ( 'Section' );
		$clinic = $this->request->params ['id'];
		
		$section = $this->Section->getAllByClinic ( $clinic );
		echo json_encode ( $section );
	}
	public function saveQueue() {
		$this->autoRender = false;
		if ($this->request->is ( 'post' )) {
			$this->loadModel ( 'Queue' );
			$this->Captcha = $this->Components->load ( 'Captcha', array (
					'model' => 'Queue',
					'action' => 'queueCaptcha' 
			) );
			$captcha = $this->Captcha->getCode ( 'Queue.security_code' );
			$this->Queue->setCaptcha ( 'security_code', $captcha );
			$this->Queue->set ( array (
					'security_code' => $this->getVal ( 'security_code' ) 
			) );
			if (! $this->Queue->validates ()) {
				AppController::applicationException ( $this->Queue->validationErrors );
			}
			
			$clinicEnglishName = parent::getClinicName ();
			$this->loadModel ( 'Clinic' );
			
			$clinic = $this->Clinic->find ( 'first', array (
					'conditions' => array (
							'Clinic.englishName' => $clinicEnglishName,
							'isEnabled' => 1 
					) 
			) );
			if (empty ( $clinic )) {
				$error = array (
						'error' => array (
								'درمانگاه وجود ندارد، یا غیرفعال شده است.' 
						) 
				);
				AppController::applicationException ( $error );
			}
			
			$this->jdatetime = $this->Components->load ( 'jdatetime' );
			
			$dateFormat = 'Y/m/d H:i:s';
			$visitDate = $this->getVal ( 'visitDate' );
			$currentDate = $this->jdatetime->shamsi ( strtotime ( date ( $dateFormat ) ), $dateFormat );
			
			$clinicId = $clinic ['Clinic'] ['id'];
			$queue = array (
					'clinic_id' => $clinicId,
					'name' => $this->getVal ( 'name' ),
					'age' => $this->getVal ( 'age' ),
					'sex' => $this->getVal ( 'sex' ),
					'telephone' => $this->getVal ( 'telephone' ),
					'registerDate' => $currentDate,
					'desc' => $this->getVal ( 'desc' ),
					'section_id' => $this->getVal ( 'section_id' ) 
			);
			$this->jdatetime->miladi ( $visitDate, $dateFormat );
			if ($visitDate < $currentDate) {
				$error = array (
						'error' => array (
								'تاریخ مراجعه گذشته است' 
						) 
				);
				AppController::applicationException ( $error );
			}
			$queue ['visitDate'] = $visitDate;
			
			$this->Queue->set ( $queue );
			if ($this->Queue->validates ()) {
				$tracking = $this->getTracking ();
				$this->Queue->set ( 'tracking', $tracking );
				$this->Queue->save ( $queue );
				echo json_encode ( array (
						'result' => "اطلاعات شما ثبت شد. شماره پیگیری: " . $tracking 
				) );
			}
			if (! empty ( $this->Queue->validationErrors )) {
				AppController::applicationException ( $this->Queue->validationErrors );
			}
		}
	}
	public function single_news() {
		$clinicEnglishName = parent::getClinicName ();
		$newsId = $this->request->params ['id'];
		$clinic = parent::setDefaultLayoutParams ( $clinicEnglishName );
		
		$this->loadModel ( "News" );
		$news = $this->News->find ( 'first', array (
				'conditions' => array (
						'id' => $newsId,
						'clinic_id' => $clinic ['id'] 
				) 
		) );
		if (empty ( $news )) {
			throw new NotFoundException ();
		}
		$news = $news ['News'];
		parent::setTitle ( $clinic ['name'] . " - " . $news ['title'] );
		$this->set ( compact ( 'news' ) );
	}
	private function getTracking() {
		$this->loadModel ( "Queue" );
		$first = true;
		while ( $first || ! empty ( $exist ) ) {
			$tracking = mt_rand ();
			$exist = $this->Queue->find ( 'first', array (
					'conditions' => array (
							'tracking' => $tracking 
					) 
			) );
			$first = false;
		}
		return $tracking;
	}
	private function getVal($name) {
		return empty ( $_POST [$name] ) ? '' : $_POST [$name];
	}
}
