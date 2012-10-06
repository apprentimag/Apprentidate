<?php

class Event extends Model {
	private $id;
	private $title;
	private $author;
	private $dates = array ();
	private $place;
	private $description;
	private $participants = array ();
	
	public function __construct () {
	}
	
	public function id () {
		return $this->id;
	}
	public function title () {
		return $this->title;
	}
	public function author ($raw = false) {
		if ($raw) {
			return $this->author;
		} else {
			return parse_user ($this->author);
		}
	}
	public function date ($pos = 0) {
		return $this->dates[$pos];
	}
	public function dates () {
		return $this->dates;
	}
	public function place () {
		return $this->place;
	}
	public function description () {
		return $this->description;
	}
	public function participants ($raw = false) {
		if ($raw) {
			return $this->participants;
		} else {
			return array_map ('parse_user', $this->participants);
		}
	}
	
	public function _id ($id) {
		$this->id = $id;
	}
	public function _title ($value) {
		$this->title = $value;
	}
	public function _author ($value) {
		$this->author = $value;
	}
	public function _dates ($value) {
		if (!is_array ($value)) {
			$value = array ($value);
		}
	
		$this->dates = $value;
	}
	public function _place ($value) {
		$this->place = $value;
	}
	public function _description ($value) {
		$this->description = $value;
	}
	public function _participants ($value) {
		if (!is_array ($value)) {
			$value = array ($value);
		}
		
		$this->participants = $value;
	}
}

class EventDAO extends Model_array {
	public function __construct () {
		parent::__construct (PUBLIC_PATH . '/data/events');
	}
	
	public function addEvent ($values) {
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
	
	public function updateEvent ($id, $values) {
		foreach ($values as $key => $value) {
			$this->array[$id][$key] = $value;
		}
		
		$this->writeFile($this->array);
	}
	
	public function deleteEvent ($id) {
		unset ($this->array[$id]);
		$this->writeFile($this->array);
	}
	
	public function listEvents () {
		$list = $this->array;
		
		if (!is_array ($list)) {
			$list = array ();
		}
		
		return HelperEvent::daoToEvent ($list);
	}
	
	public function searchById ($id) {
		$list = HelperEvent::daoToEvent ($this->array);
		
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

class HelperEvent {
	public static function daoToEvent ($listDAO) {
		$list = array ();

		if (!is_array ($listDAO)) {
			$listDAO = array ($listDAO);
		}

		foreach ($listDAO as $key => $dao) {
			$list[$key] = new Event ();
			$list[$key]->_id ($key);
			$list[$key]->_title ($dao['title']);
			$list[$key]->_author ($dao['author']);
			$list[$key]->_dates ($dao['dates']);
			$list[$key]->_place ($dao['place']);
			$list[$key]->_description ($dao['description']);
			$list[$key]->_participants ($dao['participants']);
		}

		return $list;
	}
}
