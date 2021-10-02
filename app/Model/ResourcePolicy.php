<?php
App::uses ( 'AppModel', 'Model' );

/**
 * ResoucePolicy Model
 */
class ResourcePolicy extends AppModel {
	public $validate = array (
			'resource_id' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'منبع ضروری می باشد' 
					) 
			),
			'group_id' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'گروه کاربری ضروری می باشد' 
					) 
			) 
	);
	public function beforeSave($options = array()) {
		parent::beforeSave ( $options );
		if (! isset ( $this->data [$this->alias] ['group_id'] ) || $this->data [$this->alias] ['group_id'] == - 1) {
			$this->data [$this->alias] ['group_id'] = null;
		}
		return true;
	}
}
