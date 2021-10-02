<?php
App::uses ( 'Model', 'Model' );
class AppModel extends Model {
	public function checkUnique($ignoredData, $fields, $or = true) {
		return $this->isUnique ( $fields, $or );
	}
	public function getAll() {
		$conditions = array (
				'order' => $this->alias . '.id asc' 
		);
		$Obj = $this->find ( 'all', $conditions );
		$Obj = Set::extract ( '/' . $this->alias . '/.', $Obj );
		return $Obj;
	}
	public function getAllGrid($fields, &$list, &$total, $customCondition = array()) {
		$order = array ();
		foreach ( $_POST ['order'] as $o ) {
			$order [] = $fields [$o ['column']] . ' ' . $o ['dir'];
		}
		$conditions = array (
				'order' => $order,
				'limit' => $_POST ['length'],
				'offset' => $_POST ['start'] 
		);
		$conditions = array_merge ( $conditions, $customCondition );
		$cond = array ();
		AppController::setFilter ( $this->alias, $_POST ['filter'], $cond );
		if (! empty ( $conditions ['conditions'] )) {
			$cond = array_merge ( $cond, $conditions ['conditions'] );
		}
		$conditions ['conditions'] = $cond;
		$list = $this->find ( 'all', $conditions );
		
		unset ( $conditions ['limit'] );
		$total = $this->find ( 'count', $conditions );
	}
	public function beforeSave($options = array()) {
		App::uses ( 'Sanitize', 'Utility' );
		$pattern = '/\<script.*\<\/script\>/iU'; // notice the U flag - it is important here
		foreach ( $this->data [$this->alias] as $k => $v ) {
// 			if (! isset ( $options ['html'] ) || (isset ( $options ['html'] ) && ! $options ['html'])) {
// 				$text = Sanitize::clean ( $v, array (
// 						'encode' => false,
// 						'escape' => false 
// 				) );
// 				$text = strip_tags ( $text );
// 			} else {
// 				$text = $v;
// 			}
			if (! isset ( $options ['js'] ) || (isset ( $options ['js'] ) && ! $options ['js'])) {
				$this->data [$this->alias] [$k] = preg_replace ( $pattern, '', $v );
			}
		}
	}
}

