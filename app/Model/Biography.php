<?php
App::uses ( 'AppModel', 'Model' );

/**
 * Biography Model
 */
class Biography extends AppModel {
	public $validate = array (
			'name' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'نام پزشک ضروری است' 
					) 
			),
			'detail' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'توضیحات بیوگرافی ضروری است' 
					) ,
					'required' => array (
							'rule' => array('maxLength', 1000),
							'message' => 'حداکثر تعداد کاراکتر بیوگرافی ۱۰۰۰ می باشدً'
					)
			),
			'image' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'تصویر پزشک ضروری است' 
					) 
			) 
	);
}
