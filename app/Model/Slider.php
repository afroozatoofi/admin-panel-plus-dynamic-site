<?php
App::uses ( 'AppModel', 'Model' );

/**
 * Slider Model
 */
class Slider extends AppModel {
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
