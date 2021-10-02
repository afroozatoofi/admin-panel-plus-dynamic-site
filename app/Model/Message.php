<?php
App::uses ( 'AppModel', 'Model' );

/**
 * Message Model
 */
class Message extends AppModel {
	public $belongsTo = array (
			"Sender" => array (
					'className' => 'Clinic',
					'foreignKey' => 'sender_id' 
			) 
	);
	public $hasMany = array (
			"MessageClinic" => array (
					'className' => 'MessageClinic',
					'foreignKey' => 'message_id' 
			) 
	);
	public $validate = array (
			'text' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'پیام ارسالی خالی می باشد' 
					) 
			) 
	);
}
