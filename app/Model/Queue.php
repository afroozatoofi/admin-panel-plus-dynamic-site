<?php
App::uses ( 'AppModel', 'Model' );

/**
 * Queue Model
 */
class Queue extends AppModel {
	public $useTable = 'queue';
	public $belongsTo = array (
			"Clinic" => array (
					'className' => 'Clinic',
					'foreignKey' => 'clinic_id' 
			) ,
			"Section" => array (
					'className' => 'Section',
					'foreignKey' => 'section_id'
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
			'clinic_id' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'انتخاب درمانگاه ضروری است.' 
					) 
			),
			'name' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'نام و نام خانوادگی ضروری است.' 
					),
					'maxlength' => array (
							'rule' => array (
									'maxLength',
									64 
							),
							'message' => 'فرمت نام و نام خانوادگی معتبر نمی باشد.' 
					) 
			),
			'age' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'سن ضروری است.' 
					),
					'pattern' => array (
							'rule' => '/^\d{1,3}$/',
							'message' => 'سن معتبر نمی باشد.' 
					) 
			),
			'sex' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'انتخاب جنسیت ضروری است.' 
					),
					'pattern' => array (
							'rule' => '/^\d{1}$/',
							'message' => 'فرمت جنسیت معتبر نمی باشد.' 
					) 
			),
			'telephone' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'تلفن تماس معتبر نمی باشد' 
					),
					'pattern' => array (
							'rule' => '/^\d{3,20}$/',
							'message' => 'تلفن تماس معتبر نمی باشد' 
					) 
			),
			'visitDate' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'تاریخ مراجعه معتبر نمی باشد' 
					) 
			),
			'desc' => array (
					'maxlength' => array (
							'rule' => array (
									'maxLength',
									1024 
							),
							'message' => 'فرمت توضیحات معتبر نمی باشد' 
					) 
			) 
	);
}
