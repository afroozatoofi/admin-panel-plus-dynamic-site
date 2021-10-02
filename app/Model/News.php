<?php
App::uses ( 'AppModel', 'Model' );

/**
 * News Model
 */
class News extends AppModel {
	public $useTable = 'news';
	public $validate = array (
			'title' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'عنوان خبر ضروری است' 
					) 
			),
			'summary' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'خلاصه خبر ضروری است' 
					) 
			),
			'text' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'متن خبر ضروری است' 
					) 
			),
			'image' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'تصویر خبر ضروری است' 
					) 
			) 
	);
}
