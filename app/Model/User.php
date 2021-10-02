<?php
App::uses ( 'AppModel', 'Model' );
// App::uses ( 'BlowfishPasswordHasher', 'Controller/Component/Auth' );

/**
 * User Model
 */
App::import ( 'component', 'CakeSession' );
class User extends AppModel {
	public $belongsTo = array (
			"Clinic" => array (
					'className' => 'Clinic',
					'foreignKey' => 'clinic_id' 
			) 
	);
	public $hasMany = array (
			"UserGroup" => array (
					'className' => 'UserGroup',
					'foreignKey' => 'user_id' 
			) 
	);
	public $actsAs = array (
			'Captcha' => array (
					'field' => array (
							'security_code' 
					),
					'error' => 'کد امنیتی اشتباه می باشد' 
			) 
	);
	public $validate = array (
			'username' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'فیلد نام کاربری ضروری می باشد' 
					),
					'alphaNumeric' => array (
							'rule' => 'alphaNumeric',
							'required' => true,
							'message' => 'در نام کاربری فقط استفاده از حروف و اعداد مجاز می باشد' 
					),
					'between' => array (
							'rule' => array (
									'between',
									4,
									20 
							),
							'message' => 'حداقل طول نام کاربری 4 و حداکثر 20 کاراکتر می باشد' 
					),
					'checkUnique' => array (
							'rule' => 'isUnique',
							'message' => 'نام کاربری تکراری می باشد' 
					),
					'pattern' => array (
							'rule' => '/^[a-zA-Z.]*$/',
							'message' => 'نام کاربری شامل حروف غیرمجاز می باشد' 
					) 
			),
			'clinic_id' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'انتخاب درمانگاه ضروری می باشد' 
					) 
			),
			'password' => array (
					'required' => array (
							'rule' => array (
									'checkPass' 
							),
							'message' => 'حداقل طول گذرواژه 4 کاراکتر می باشد' 
					) 
			),
			'confirmPassword' => array (
					'matchNew' => array (
							'rule' => array (
									'matchNewPassword' 
							),
							'message' => 'گذرواژه جدید و تکرار آن برابر نیستند' 
					) 
			),
			'oldPassword' => array (
					'matchOld' => array (
							'rule' => array (
									'matchOldPassword' 
							),
							'message' => 'گذرواژه قبلی اشتباه است' 
					) 
			) 
	);
	public function matchNewPassword() {
		if ($this->data [$this->alias] ['confirmPassword'] == $this->data [$this->alias] ['password']) {
			return true;
		}
		return false;
	}
	public function matchOldPassword() {
		if (! $this->matchNewPassword ()) {
			return true;
		}
		$user = $this->find ( 'first', array (
				'User.id' => CakeSession::read ( 'Auth.User.id' ) 
		) );
		$storeHash = $user [$this->alias] ['password'];
		$newHash = Security::hash ( $this->data [$this->alias] ['oldPassword'], 'blowfish', $storeHash );
		return $newHash == $storeHash;
	}
	public function checkPass() {
		$pass = $this->data [$this->alias] ["password"];
		if (! empty ( $this->data [$this->alias] ["id"] )) {
			return empty ( $pass ) || mb_strlen ( $pass ) >= 4;
		} else {
			return ! empty ( $pass ) && mb_strlen ( $pass ) >= 4;
		}
	}
	public function beforeSave($options = array()) {
		parent::beforeSave ( $options );
		unset ( $this->data [$this->alias] ['isAdmin'] );
		if (empty ( $this->data [$this->alias] ['password'] )) {
			unset ( $this->data [$this->alias] ['password'] );
		} else {
			$this->data [$this->alias] ['password'] = Security::hash ( $this->data [$this->alias] ['password'], 'blowfish', Configure::read ( 'Security.salt' ) );
		}
		if (! empty ( $this->data [$this->alias] ['username'] )) {
			$this->data [$this->alias] ['username'] = strtolower ( $this->data [$this->alias] ['username'] );
		}
		return true;
	}
// 	public function afterFind($results, $primary = false) {
// 		return Hash::remove ( $results, '{n}.' . $this->alias . '.password' );
// 	}
}
