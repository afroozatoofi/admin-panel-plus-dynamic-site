<?php
App::uses ( 'AppModel', 'Model' );

/**
 * ContactUs Model
 */
class ContactTel extends AppModel {
	public $validate = array (
			'telephone' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'تلفن وارد شده معتبر نمی باشد' 
					),
					'checkUnique' => array (
							'rule' => array (
									'checkUnique',
									array (
											'telephone',
											'contact_id' 
									),
									false 
							),
							'message' => 'شماره تلفن وارد شده تکراری است.' 
					) 
			) 
	);
}
