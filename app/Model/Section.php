<?php
App::uses ( 'AppModel', 'Model' );

/**
 * Section Model
 */
class Section extends AppModel {
	public $validate = array (
			'title' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'عنوان بخش ضروری می باشد' 
					) 
			),
			'description' => array (
					'required' => array (
							'rule' => array (
									'notEmpty' 
							),
							'message' => 'توضیحات بخش ضروری می باشد' 
					) 
			) 
	);
	public function getAllByClinic($clinic) {
		$conditions = array (
				'order' => 'id asc',
				'conditions' => array (
						'clinic_id' => $clinic 
				),
				'fields' => array (
						'id',
						'title' 
				) 
		);
		$section = $this->find ( 'all', $conditions );
		$section = Set::extract ( '/Section/.', $section );
		return $section;
	}
}
