<?php
App::uses ( 'AppModel', 'Model' );

/**
 * ContactUs Model
 */
class ContactUs extends AppModel {
	public $useTable = 'contact_us';
	public $hasMany = array (
			"Tels" => array (
					'className' => 'ContactTel',
					'foreignKey' => 'contact_id',
					'order' => 'id' 
			) 
	);
	const POSITION = '/^[-+]?[0-9]{0,3}(\\.{1}[0-9]{0,15})?$/';
	public $validate = array (
			'clinic_id' => array (
					'checkUnique' => array (
							'rule' => 'isUnique',
							'message' => 'برای این درمانگاه قبلا تلفن ثبت شده است.',
							'allowEmpty' => true 
					) 
			),
			'email' => array (
					'allowEmpty' => true,
					'rule' => 'email',
					'message' => 'پست الکترونیک معتبر نمی باشد' 
			),
			'postal_code' => array (
					'allowEmpty' => true,
					'rule' => '/^\d{10}$/',
					'message' => 'کد پستی معتبر نمی باشد' 
			),
			'latitude' => array (
					'decimal' => array (
							'allowEmpty' => true,
							'rule' => array (
									'decimal',
									null,
									ContactUs::POSITION 
							),
							'message' => 'فرمت عرض جغرافیایی معتبر نمی باشد' 
					) 
			),
			'longitude' => array (
					'decimal' => array (
							'allowEmpty' => true,
							'rule' => array (
									'decimal',
									null,
									ContactUs::POSITION 
							),
							'message' => 'فرمت طول جغرافیایی معتبر نمی باشد' 
					) 
			) 
	)
	;
}
