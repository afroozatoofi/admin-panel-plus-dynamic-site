<?php
App::uses ( 'AppModel', 'Model' );

/**
 * Gallery Model
 */
class Gallery extends AppModel {
	public $useTable = 'gallery';
	public $validate = array (
			'image' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'تصویر انتخاب نشده است' 
					) 
			) 
	);
}
