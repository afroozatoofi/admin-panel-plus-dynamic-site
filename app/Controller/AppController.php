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

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $components = array (
			'Session',
			'Auth' => array (
					'loginRedirect' => array (
							'controller' => 'pages',
							'action' => 'home' 
					),
					'logoutRedirect' => array (
							'controller' => 'users',
							'action' => 'login' 
					),
					'authError' => 'نام کاربری یا گذرواژه اشتباه می باشد',
					'authenticate' => array (
							'Form' => array (
									'passwordHasher' => 'Blowfish',
									'scope' => array (
											'User.isEnabled' => 1,
											'Clinic.isEnabled' => 1 
									) 
							) 
					),
					'authorize' => array (
							'Controller' 
					) 
			) 
	);
	public static $dataSource;
	public function isAuthorized($user) {
		if ($this->action === 'home') {
			return true;
		}
		$admin = CakeSession::read ( 'Auth.User.isAdmin' );
		if ($admin == 1) {
			return true;
		}
		$this->redirect ( array (
				'controller' => 'pages',
				'action' => 'home' 
		) );
		return false;
	}
	public static function getTitle($title) {
		$resources = CakeSession::read ( 'Auth.User.resources' );
		if (! empty ( $resources )) {
			$title = strtolower ( $title );
			foreach ( $resources as $resource ) {
				if (! empty ( $resource ['children'] )) {
					$G = $resource ['Resource'] ['name'];
					foreach ( $resource ['children'] as $m ) {
						if (str_replace ( "_", "", $m ['Resource'] ['url'] ) == $title) {
							return $G . " - " . $m ['Resource'] ['name'];
						}
					}
				}
			}
		}
		return false;
	}
	function beforeRender() {
		if ($this->name == 'CakeError') {
			$this->layout = 'error';
		}
	}
	public function beforeFilter() {
	}
	public function loadById($model, $id, $cond = null) {
		if ($this->$model->exists ( $id )) {
			$options = array (
					'conditions' => array (
							$model . '.' . $this->$model->primaryKey => $id 
					) 
			);
			if (! empty ( $cond )) {
				$options ['conditions'] = array_merge ( $options ['conditions'], $cond );
			}
			return $this->$model->find ( 'first', $options );
		}
		return null;
	}
	function endsWith($haystack, $needle) {
		// search forward starting from end minus needle length characters
		return $needle === "" || strpos ( $haystack, $needle, strlen ( $haystack ) - strlen ( $needle ) ) !== FALSE;
	}
	public function getMaxCode($model) {
		$conditions = array (
				'order' => 'code desc' 
		);
		$obj = $this->$model->find ( 'first', $conditions );
		$obj = Set::extract ( '/' . $this->model . '/.', $obj );
		return $obj ? $obj [0] ['code'] + 1 : 1;
	}
	public function getAllGrid($model, $fields, &$list, &$total, $customCondition = array()) {
		$order = array ();
		foreach ( $_POST ['order'] as $o ) {
			$order [] = $fields [$o ['column']] . ' ' . $o ['dir'];
		}
		$conditions = array (
				'order' => $order,
				'limit' => $_POST ['length'],
				'offset' => $_POST ['start'] 
		);
		$conditions = array_merge ( $conditions, $customCondition );
		$cond = array ();
		$this->setFilter ( $model, $_POST ['filter'], $cond );
		if (! empty ( $conditions ['conditions'] )) {
			$cond = array_merge ( $cond, $conditions ['conditions'] );
		}
		$conditions ['conditions'] = $cond;
		$list = $this->$model->find ( 'all', $conditions );
		
		unset ( $conditions ['limit'] );
		$total = $this->$model->find ( 'count', $conditions );
	}
	public function setFilter($model, $filter, &$cond) {
		if (! empty ( $filter )) {
			parse_str ( $filter, $queryString );
			foreach ( $queryString as $k => $v ) {
				if ((! empty ( $v ) || ($v === 0 || $v === "0")) && ! ($v === - 1 || $v === "-1")) {
					$mdl = $model;
					if (strpos ( $k, '*' ) !== false) {
						list ( $mdl, $k ) = explode ( '*', $k );
					}
					if (is_array ( $v ) && count ( $v ) == 1) {
						$v = $v [0];
					}
					if (is_array ( $v )) {
						$cond [$mdl . "." . $k . ' IN'] = $v;
					} else if (is_numeric ( $v )) {
						$cond [$mdl . "." . $k . ' LIKE'] = $v;
					} else if (strpos ( $k, '>' ) === false && strpos ( $k, '<' ) === false) {
						$cond [$mdl . "." . $k . ' LIKE'] = '%' . $this->persian ( $v ) . '%';
					} else {
						$cond [] = $k . '"' . $this->persian ( $v ) . '"';
					}
				}
			}
		}
	}
	public function printGridInfo($list, $total) {
		echo json_encode ( array (
				'data' => $list,
				'recordsTotal' => $total,
				'recordsFiltered' => $total 
		) );
	}
	public function deleteById($model, $id) {
		$this->$model->id = $id;
		if ($this->$model->exists ()) {
			try {
				if ($this->$model->delete ()) {
					return true;
				}
			} catch ( PDOException $e ) {
				$error = array (
						'constraint' => array (
								'بدلیل استفاده رکورد در فرم های دیگر، امکان حذف آن وجود ندارد' 
						) 
				);
				AppController::applicationException ( $error );
			}
		}
		return false;
	}
	public function deleteAll($model, $cond) {
		$this->$model->deleteAll ( $cond );
	}
	public function getAllData($model) {
		$conditions = array (
				'order' => 'id asc' 
		);
		$Obj = $this->$model->find ( 'all', $conditions );
		$Obj = Set::extract ( '/' . $model . '/.', $Obj );
		return $Obj;
	}
	public function upload($dir, $name, $allowExt = null, $maxSize = null) {
		$imageName = basename ( $_FILES [$name] ['name'] );
		$imageSize = $_FILES [$name] ['size'];
		$imageExt = strtolower ( pathinfo ( $imageName, PATHINFO_EXTENSION ) );
		
		if ($allowExt == null) {
			$allowExt = array (
					'jpg',
					'jpeg',
					'png' 
			);
		}
		if ($maxSize == null) {
			$maxSize = 2 * 1024 * 1024;
		}
		if ($imageSize > $maxSize) {
			$error = array (
					'limit' => array (
							'حداکثر حجم فایل 2MB میتواند باشد' 
					) 
			);
			AppController::applicationException ( $error );
		}
		if (! in_array ( $imageExt, $allowExt )) {
			$error = array (
					'limit' => array (
							'فرمت  انتخاب شده پشتیبانی نمی شود' 
					) 
			);
			AppController::applicationException ( $error );
		}
		
		$imageName = uniqid ( "", true ) . '.' . $imageExt;
		while ( file_exists ( $dir . $imageName ) ) {
			$imageName = uniqid ( "", true ) . '.' . $imageExt;
		}
		$image = $dir . $imageName;
		move_uploaded_file ( $_FILES [$name] ["tmp_name"], $image );
		App::uses ( 'ImageHelper', 'Controller/Helper' );
		ImageHelper::compressImage ( $image );
		return $imageName;
	}
	public static function applicationException($errors) {
		if (! is_array ( $errors )) {
			$errors = array (
					'error' => array (
							$errors
					)
			);
		}
		throw new Exception ( json_encode ( $errors ) );
		die ();
	}
	public static function setClinicAccess(&$cond, $model = null) {
		if (! empty ( $model )) {
			$model .= '.';
		}
		$cond [$model . 'clinic_id'] = CakeSession::read ( 'Auth.User.clinic_id' );
	}
	public static function checkClinicAccess($clinic) {
		if ($clinic != CakeSession::read ( 'Auth.User.clinic_id' )) {
			$error = array (
					'error' => array (
							'امکان دسترسی به امکانات درخواستی وجود ندارد' 
					) 
			);
			AppController::applicationException ( $error );
		}
	}
	public function CKEditorUpload($target_dir, $name, $allowExt = null, $maxSize = null) {
		try {
			$upload = $this->upload ( $target_dir, $name, $allowExt, $maxSize );
			$target_file = $target_dir . $upload;
			echo "<script>window.parent.CKEDITOR.tools.callFunction(" . $_GET ['CKEditorFuncNum'] . ", \"" . $this->webroot . $target_file . "\");</script>";
		} catch ( Exception $e ) {
			echo "<script>window.parent.showExp('" . $e->getMessage () . "');</script>";
		}
	}
	public function persian($string) {
		if (empty ( $string )) {
			return "";
		}
		
		$arabic = array (
				'ي',
				'ك' 
		);
		$persian = array (
				'ی',
				'ک' 
		);
		
		return str_replace ( $arabic, $persian, $string );
	}
}
