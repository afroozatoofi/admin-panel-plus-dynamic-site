<?php
App::uses ( 'AppController', 'Controller' );
class UsersController extends AppController {
	var $components = array (
			'Captcha' => array (
					'field' => 'security_code' 
			) 
	);
	var $model = 'User';
	public function isAuthorized($user) {
		if ($this->action === 'changePass') {
			return true;
		}
		if (isset ( $user ['forms'] ) && in_array ( 3, $user ['forms'] )) {
			return true;
		}
		return parent::isAuthorized ( $user );
	}
	public function beforeFilter() {
		parent::beforeFilter ();
		$this->Auth->allow ( 'add', 'logout', 'captcha' );
	}
	public function getAllGroup() {
		$this->autoRender = false;
		$this->loadModel ( 'Group' );
		echo json_encode ( $this->Group->getAll () );
	}
	function captcha() {
		$this->autoRender = false;
		$this->layout = 'ajax';
		if (! isset ( $this->Captcha )) {
			$this->Captcha = $this->Components->load ( 'Captcha' );
		}
		$this->Captcha->create ();
	}
	public function manageUsers() {
		// for view
	}
	public function changePass() {
		$this->autoRender = false;
		$this->User->set ( $this->request->data );
		if ($this->User->validates ( array (
				'fieldList' => array (
						'password',
						'confirmPassword',
						'oldPassword' 
				) 
		) )) {
			$this->request->data [$this->model] ['id'] = $this->Auth->user ( 'id' );
			unset ( $this->request->data [$this->model] ['oldPassword'] );
			unset ( $this->request->data [$this->model] ['confirmPassword'] );
			$this->request->data [$this->model] ['username'] = $this->Auth->user ( 'username' );
			$this->User->save ( $this->request->data );
			
			if (! empty ( $this->User->validationErrors )) {
				parent::applicationException ( $this->User->validationErrors );
			}
		} else {
			parent::applicationException ( $this->User->validationErrors );
		}
	}
	public function login() {
		if ($this->request->is ( 'post' )) {
			$this->Auth->logout ();
			// ob_start();
			$this->autoRender = false;
			$captcha = $this->Captcha->getCode ( 'User.security_code' );
			$this->User->setCaptcha ( 'security_code', $captcha );
			$this->User->set ( $this->request->data );
			
			if ($this->User->validates ( array (
					'fieldList' => array (
							'security_code',
							'password' 
					) 
			) )) {
				$this->request->data [$this->model] ['username'] = strtolower ( $this->request->data [$this->model] ['username'] );
				$login = $this->Auth->login ();
				// ob_end_clean();
				if ($login) {
					
					$this->loadModel ( 'UserGroup' );
					$this->loadModel ( 'Resource' );
					$this->loadModel ( 'ResourcePolicy' );
					
					$clinicId = $this->Auth->user ( 'clinic_id' );
					
					if ($this->Auth->user ( 'isAdmin' ) != 1) {
						
						$userGroup = $this->UserGroup->find ( 'list', array (
								'fields' => array (
										'group_id' 
								),
								'conditions' => array (
										'user_id' => $this->Auth->user ( 'id' ) 
								) 
						) );
						
						$userPolicy = $this->ResourcePolicy->find ( 'list', array (
								'fields' => array (
										'resource_id' 
								),
								'conditions' => array (
										'group_id' => $userGroup 
								) 
						) );
						
						$resourceCode = $this->Resource->find ( 'list', array (
								'fields' => 'code',
								'conditions' => array (
										array (
												'id' => $userPolicy 
										) 
								) 
						) );
						
						$userResource = $this->Resource->find ( 'threaded', array (
								'conditions' => array (
										'OR' => array (
												array (
														'parent_id' => null 
												),
												array (
														'id' => $userPolicy 
												) 
										) 
								),
								'order' => 'index' 
						) );
					} else {
						$resourceCode = array ();
						$rss = array ();
						$userResource = $this->Resource->find ( 'threaded', array (
								'order' => 'index' 
						) );
					}
					
					$this->Session->write ( AuthComponent::$sessionKey, array_merge ( array (
							'resources' => $userResource,
							'forms' => $resourceCode 
					), $this->Auth->user () ) );
					
					echo json_encode ( $this->Auth->redirect () );
				} else {
					$user = $this->User->find ( 'first', array (
							'conditions' => array (
									'User.username' => $this->request->data [$this->model] ['username'] 
							) 
					) );
					$err = null;
					if (! empty ( $user )) {
						if ($user [$this->model] ['isEnabled'] == 0 || $user ['Clinic'] ['isEnabled'] == 0) {
							$encrypt = Security::hash ( $this->request->data [$this->model] ['password'], 'blowfish', Configure::read ( 'Security.salt' ) );
							if ($encrypt == $user [$this->model] ['password']) {
								$err = "حساب کاربری شما غیر فعال شده است، لطفا با مدیر سایت تماس بگیرید.";
							}
						}
					}
					if (empty ( $err )) {
						$err = $this->Auth->authError;
					}
					$err = array (
							'auth' => array (
									$err 
							) 
					);
					parent::applicationException ( $err );
				}
			} else {
				$this->Captcha->deleteCode ( 'User.security_code' );
				parent::applicationException ( $this->User->validationErrors );
			}
		} else if ($this->Auth->loggedIn ()) {
			$this->redirect ( $this->Auth->redirect () );
		}
		$title = 'ورود به سامانه';
		$this->set ( compact ( 'title' ) );
	}
	public function logout() {
		$url = $this->Auth->logout ();
		return $this->redirect ( $url );
	}
	public function load($id = null) {
		$this->autoRender = false;
		$Obj = parent::loadById ( $this->model, $id );
		
		if ($Obj) {
			$Obj [$this->model] ['password'] = '';
			foreach ( $Obj ["UserGroup"] as $i => $UG ) {
				$Obj ["UserGroup"] [$i] ['id'] = $UG ['group_id'];
				unset ( $Obj ["UserGroup"] [$i] ['group_id'] );
				unset ( $Obj ["UserGroup"] [$i] ['user_id'] );
			}
			echo json_encode ( $Obj );
		}
	}
	public function view() {
		$this->autoRender = false;
		
		$fields = array (
				'User.id',
				'Clinic.name',
				$this->model . '.username',
				$this->model . '.name',
				$this->model . '.family',
				$this->model . '.isEnabled' 
		);
		
		parent::getAllGrid ( $this->model, $fields, $list, $total );
		
		foreach ( $list as $K => $R ) {
			$list [$K] [$this->model] ['isEnabled'] = $R [$this->model] ['isEnabled'] == 1 ? 'فعال' : 'غیرفعال';
			$list [$K] [$this->model] ['clinic'] = $R ['Clinic'] ['name'];
			unset ( $list [$K] [$this->model] ['password'] );
		}
		$list = Set::extract ( '/' . $this->model . '/.', $list );
		parent::printGridInfo ( $list, $total );
	}
	public function save() {
		$this->autoRender = false;
		if ($this->request->is ( 'post' )) {
			$this->request->data [$this->model] ['isEnabled'] = isset ( $this->request->data [$this->model] ['isEnabled'] ) ? 1 : 0;
			$this->User->create ();
			
			$gIds = array ();
			
			if (isset ( $this->request->data ['UserGroup'] ['id'] )) {
				foreach ( $this->request->data ['UserGroup'] ['id'] as $gid ) {
					$gIds [] = array (
							'group_id' => $gid 
					);
				}
			}
			$this->request->data ['UserGroup'] = $gIds;
			$id = $this->request->data [$this->model] ['id'];
			if ($id && $id > 0) {
				$this->User->UserGroup->deleteAll ( array (
						'user_id' => $id 
				), false );
			}
			if ($this->User->saveAssociated ( $this->request->data )) {
				echo $this->User->id;
			}
		}
		if (! empty ( $this->User->validationErrors )) {
			parent::applicationException ( $this->User->validationErrors );
		}
	}
}

