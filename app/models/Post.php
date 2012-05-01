<?php


class Post extends Kate {
	
	public $source = array(
		'type' => 'db',
		'table' => array(
			'name' => array('n'=>'news'),
			'primary' => 'nid',
			'fields' => '*',
			'nowFields' => 'dateadded'
		)
	);	
	
	protected function _selectPrimary($select, $primary) {
		
		if(!is_numeric($primary)) {
			$select = $this->_parseQuery($select,array(
				'lower permalink' => strtolower($primary)
			));
		} else $select->where('n.nid = ?',$primary);
		
		return $select;	
	}
	
	
	public function permalink($end = '') {
		
		$data = $this->getData();
		
		$time = strtotime($data['dateadded']);
		
		$permalink = '/nouvelles/'.date('Y',$time).'/'.date('m',$time).'/'.$data['permalink'];
		
		if(!empty($end)) return $permalink.'/'.ltrim($end,'/');
		else return $permalink.'.html';
			
	}
	
	
	
	/*
	 *
	 * Validate user data
	 *
	 */
	public function validate() {
		
		$data = $this->getData();
		
		if(!isset($data['title']) || empty($data['title'])) {
			Gregory::get()->addError('Vous devez entrer un titre');
		}
		
		if(!isset($data['body']) || empty($data['body'])) {
			Gregory::get()->addError('Vous devez entrer un texte');
		}
		
	}
	
	
	/*
	 *
	 * Items query custom parameters
	 *
	 */
	 
	protected function _queryAvailable($select, $value) {
		
		if($value === true || $value === 1 || $value === '1' || $value === 'true') {
			$select->where('n.published = 1 AND n.deleted = 0');
		} else if($value === false || $value === 0 || $value === '0' || $value === 'false') {
			$select->where('n.published = 0 OR n.deleted = 1');
		}
		
		return $select;	
	}
	
}
