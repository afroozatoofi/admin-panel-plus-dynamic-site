<?php
App::uses ( 'AppModel', 'Model' );

/**
 * MessageClinic Model
 */
class MessageClinic extends AppModel {
	public $belongsTo = array (
			"Clinic" => array (
					'className' => 'Clinic',
					'foreignKey' => 'receiver_id' 
			),
			"Message" => array (
					'className' => 'Message',
					'foreignKey' => 'message_id' 
			) 
	);
}
