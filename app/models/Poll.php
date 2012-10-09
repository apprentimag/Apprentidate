<?php

class Poll extends Model {
	private $id;
	private $idEvent;
	private $title;
	private $choices = array ();
	private $voters = array ();
	
	public function __construct () {
	}
	
	public function id () {
		return $this->id;
	}
	public function idEvent () {
		return $this->idEvent;
	}
	public function title () {
		return $this->title;
	}
	public function choices () {
		return $this->choices;
	}
	public function voters () {
		return $this->voters;
	}
	
	public function _id ($id) {
		$this->id = $id;
	}
	public function _idEvent ($id) {
		$this->idEvent = $id;
	}
	public function _title ($value) {
		$this->title = $value;
	}
	public function _choices ($value) {
		if (!is_array ($value)) {
			$value = array ($value);
		}
		
		$this->choices = $value;
	}
	public function _voters ($value) {
		if (!is_array ($value)) {
			$value = array ($value);
		}
		
		$this->voters = $value;
	}
}

class PollDAO extends Model_array {
	public function __construct () {
		parent::__construct (PUBLIC_PATH . '/data/polls');
	}
	
	public function addPoll ($values) {
		$id = $this->generateKey ($values['title']);
		
		if (!isset ($this->array[$id])) {
			$this->array[$id] = array ();
		
			foreach ($values as $key => $value) {
				$this->array[$id][$key] = $value;
			}
		
			$this->writeFile($this->array);
		
			return $id;
		} else {
			return false;
		}
	}
	
	public function updatePoll ($id, $values) {
		foreach ($values as $key => $value) {
			$this->array[$id][$key] = $value;
		}
		
		$this->writeFile($this->array);
	}
	
	public function vote ($id, $values) {
		if (isset ($this->array[$id])) {
			$name = $values['voter'];
			$choices = $values['choices'];
			
			if (is_array ($choices)) {
				$this->array[$id]['voters'][$name] = $choices;
			
				$this->writeFile($this->array);
			}
		}
	}
	
	public function deletePoll ($id) {
		unset ($this->array[$id]);
		$this->writeFile($this->array);
	}
	
	public function listPolls () {
		$list = $this->array;
		
		if (!is_array ($list)) {
			$list = array ();
		}
		
		return HelperPoll::daoToPoll ($list);
	}
	
	public function listByEventId ($id) {
		$list = array ();
		
		foreach ($this->array as $key => $poll) {
			if ($poll['idEvent'] == $id) {
				$list[$key] = $poll;
			}
		}
		
		return HelperPoll::daoToPoll ($list);
	}
	
	public function searchById ($id) {
		$list = HelperPoll::daoToPoll ($this->array);
		
		if (isset ($list[$id])) {
			return $list[$id];
		} else {
			return false;
		}
	}
	
	private function generateKey ($sel) {
		return small_hash ($sel . time () . Configuration::selApplication ());
	}
	
	public function count () {
		return count ($this->array);
	}
}

class HelperPoll {
	public static function daoToPoll ($listDAO) {
		$list = array ();

		if (!is_array ($listDAO)) {
			$listDAO = array ($listDAO);
		}

		foreach ($listDAO as $key => $dao) {
			$list[$key] = new Poll ();
			$list[$key]->_id ($key);
			$list[$key]->_idEvent ($dao['idEvent']);
			$list[$key]->_title ($dao['title']);
			$list[$key]->_choices ($dao['choices']);
			$list[$key]->_voters ($dao['voters']);
		}

		return $list;
	}
}
