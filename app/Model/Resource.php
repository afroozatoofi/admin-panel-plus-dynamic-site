<?php
App::uses ( 'AppModel', 'Model' );

/**
 * Resource Model
 */
class Resource extends AppModel {
	public $validate = array (
			'name' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'نام منبع ضروری می باشد' 
					),
					'length' => array (
							'rule' => array (
									'maxLength',
									'50' 
							),
							'message' => 'حداکثر طول نام منبع 50 کاراکتر می باشد' 
					) 
			),
			'url' => array (
					'length' => array (
							'rule' => array (
									'maxLength',
									'50' 
							),
							'message' => 'حداکثر طول آدرس 50 کاراکتر می باشد' 
					),
					"checkNullUrl" => array (
							"rule" => array (
									"checkNullUrl" 
							),
							"message" => "فیلد آدرس در منوها ضروری می باشد" 
					) 
			),
			'englishName' => array (
					"checkNullEnglishName" => array (
							"rule" => array (
									"checkNullEnglishName" 
							),
							"message" => "نام انگلیسی در منوها ضروری می باشد" 
					),
					'length' => array (
							'rule' => array (
									'maxLength',
									'50' 
							),
							'message' => 'حداکثر طول نام انگلیسی 50 کاراکتر می باشد' 
					),
					'checkUnique' => array (
							'rule' => 'isUnique',
							'message' => 'نام انگلیسی تکراری می باشد',
							'allowEmpty' => true 
					) 
			),
			'code' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'کد ضروری می باشد' 
					),
					'checkNullCode' => array (
							"rule" => array (
									"checkNullCode" 
							),
							"message" => "کد برای منوها ضروری می باشد" 
					),
					'checkUnique' => array (
							'rule' => 'isUnique',
							'message' => 'کد منبع تکراری می باشد',
							'allowEmpty' => true 
					) 
			),
			'class' => array (
					'length' => array (
							'rule' => array (
									'maxLength',
									'50' 
							),
							'message' => 'حداکثر طول نام کلاس 50 کاراکتر می باشد' 
					) 
			),
			'index' => array (
					'number' => array (
							'rule' => array (
									'numeric' 
							),
							'message' => 'نوع فیلد ترتیب عددی می باشد',
							'allowEmpty' => true 
					) 
			),
			'parent_id' => array (
					"checkNullParent" => array (
							"rule" => array (
									"checkNullParent" 
							),
							"message" => "دسته بندی در منوها ضروری می باشد" 
					),
					"checkFullParent" => array (
							"rule" => array (
									"checkFullParent" 
							),
							"message" => "دسته بندی باید خالی باشد" 
					) 
			) 
	);
	public function checkNullUrl() {
		if ($this->data [$this->alias] ["type"] == 2) {
			return ! empty ( $this->data [$this->alias] ['url'] );
		}
		return true;
	}
	public function checkNullEnglishName() {
		if ($this->data [$this->alias] ["type"] == 2) {
			return ! empty ( $this->data [$this->alias] ['englishName'] );
		}
		return true;
	}
	public function checkNullParent() {
		if ($this->data [$this->alias] ["type"] == 2) {
			return ! empty ( $this->data [$this->alias] ['parent_id'] ) && $this->data [$this->alias] ['parent_id'] != - 1;
		}
		return true;
	}
	public function checkNullCode() {
		if ($this->data [$this->alias] ["type"] == 2 && empty ( $this->data [$this->alias] ['id'] )) {
			return ! empty ( $this->data [$this->alias] ['code'] );
		}
		return true;
	}
	public function checkFullParent() {
		if ($this->data [$this->alias] ["type"] == 1) {
			return empty ( $this->data [$this->alias] ['parent_id'] ) || $this->data [$this->alias] ['parent_id'] == - 1;
		}
		return true;
	}
	public function beforeSave($options = array()) {
		parent::beforeSave ( $options );
		if (empty ( $this->data [$this->alias] ['url'] )) {
			$this->data [$this->alias] ['url'] = null;
		}
		if (empty ( $this->data [$this->alias] ['englishName'] )) {
			$this->data [$this->alias] ['englishName'] = null;
		}
		if (! empty ( $this->data [$this->alias] ['parent_id'] ) && $this->data [$this->alias] ['parent_id'] == - 1) {
			$this->data [$this->alias] ['parent_id'] = null;
		}
		if (! empty ( $this->data [$this->alias] ['id'] )) {
			unset ( $this->data [$this->alias] ['code'] );
		}
		return true;
	}
	public function getAll() {
		$conditions = array (
				'order' => 'parent_id asc, id asc',
				'conditions' => array (
						'type' => 2 
				) 
		);
		$list = $this->find ( 'all', $conditions );
		$list = Set::extract ( '/' . $this->alias . '/.', $list );
		return $list;
	}
}
