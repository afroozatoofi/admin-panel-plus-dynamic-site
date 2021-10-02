<?php
App::uses ( 'AppModel', 'Model' );

/**
 * AboutUs Model
 */
class AboutUs extends AppModel {
	public $useTable = 'about_us';
	public $validate = array (
			'summary' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'خلاصه درباره ما نمیتواند ضروری است.' 
					) 
			),
			'description' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'درباره ما ضروری است.' 
					) 
			) 
	);
}
