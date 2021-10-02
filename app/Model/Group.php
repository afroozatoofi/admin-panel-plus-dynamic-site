<?php
App::uses ( 'AppModel', 'Model' );

/**
 * Group Model
 */
class Group extends AppModel {
	public $validate = array (
			'name' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'نام گروه ضروری می باشد' 
					),
					'checkUnique' => array (
							'rule' => 'isUnique',
							'message' => 'نام گروه انتخاب شده تکراری می باشد' 
					) 
			) 
	);
}
