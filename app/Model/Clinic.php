<?php
App::uses ( 'AppModel', 'Model' );

/**
 * Clinic Model
 */
class Clinic extends AppModel {
	public $validate = array (
			'name' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'نام درمانگاه ضروری می باشد' 
					),
					'checkUnique' => array (
							'rule' => 'isUnique',
							'message' => 'نام درمانگاه تکراری می باشد' 
					) 
			),
			'englishName' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'نام انگلیسی درمانگاه ضروری می باشد' 
					),
					'checkUnique' => array (
							'rule' => 'isUnique',
							'message' => 'نام انگلیسی درمانگاه تکراری می باشد',
							'allowEmpty' => true 
					),
					'pattern' => array (
							'rule' => '/^[a-zA-Z.]*$/',
							'message' => 'نام انگلیسی شامل حروف غیرمجاز می باشد' 
					) 
			) 
	);
	function getAllOther() {
		$conditions = array (
				'fields' => array (
						'id',
						'name' 
				),
				'order' => 'name asc',
				'conditions' => array (
						'id !=' => CakeSession::read ( 'Auth.User.clinic_id' ),
						'isEnabled' => 1 
				),
				'recursive' => - 1 
		);
		$Obj = $this->find ( 'all', $conditions );
		$Obj = Set::extract ( '/' . $this->alias . '/.', $Obj );
		return $Obj;
	}
}
