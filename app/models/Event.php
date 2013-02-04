<?php

class Event extends Model {
	private $idEvent;
	private $title;
	private $author;
	private $date;
	private $place;
	private $description;
	private $expirationdate;
	private $participants = array ();
	
	public function __construct () {
	}
	
	public function id () {
		return $this->idEvent;
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
	public function date () {
		return $this->date;
	}
	public function place () {
		return $this->place;
	}
	public function description ($raw = false) {
		if ($raw) {
			return $this->description;
		} else {
			return nl2br ($this->description);
		}
	}
	public function participants ($raw = false) {
		if ($raw) {
			return $this->participants;
		} else {
			return array_map ('parse_user', $this->participants);
		}
	}
	public function expirationdate () {
		return $this->expirationdate;
	}
	
	public function _id ($id) {
		$this->idEvent = $id;
	}
	public function _title ($value) {
		$this->title = $value;
	}
	public function _author ($value) {
		$this->author = $value;
	}
	public function _date ($value) {
		$this->date = $value;
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
	public function _expirationdate($value) {
		$this->expirationdate = $value;
	}
}

class EventDAO extends Model_pdo {
	
	public function addEvent ($values) {
		$sql = 'INSERT INTO events (title, author, date, place, description, expirationdate) VALUES(?, ?, ?, ?, ?, ?)';
		$stm = $this->bd->prepare ($sql);

		$values = array (
			$values['title'],
			$values['author'],
			$values['date'],
			$values['place'],
			$values['description'],
			$values['expirationdate'],
		);

		if ($stm && $stm->execute ($values)) {
			return $this->bd->lastInsertId();
		} else {
			return false;
		}
	}
	
	public function updateEvent ($id, $values) {
		$sql = 'UPDATE events SET title=?, author=?, date=?, place=?, description=?, expirationdate=? WHERE idEvent=?';
		$stm = $this->bd->prepare ($sql);

		$values = array (
			$values['title'],
			$values['author'],
			$values['date'],
			$values['place'],
			$values['description'],
			$values['expirationdate'],
			$id
		);

		if ($stm && $stm->execute ($values)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function deleteEvent ($id) {
		$sql = 'DELETE FROM events WHERE idEvent=?';
		$stm = $this->bd->prepare ($sql);

		$values = array ($id);

		if ($stm && $stm->execute ($values)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function listEvents () {
		$sql = 'SELECT * FROM events ORDER BY date';
		$stm = $this->bd->prepare ($sql);
		$stm->execute ();

		return HelperEvent::daoToEvent ($stm->fetchAll (PDO::FETCH_ASSOC));
	}
	
	public function searchById ($id) {
		$sql = 'SELECT * FROM events WHERE idEvent=?';
		$stm = $this->bd->prepare ($sql);

		$values = array ($id);

		$stm->execute ($values);
		$res = $stm->fetchAll (PDO::FETCH_ASSOC);
		$values = HelperEvent::daoToEvent ($res);

		if (isset ($values[0])) {
			return $values[0];
		} else {
			return false;
		}
	}
	
	public function count () {
		$sql = 'SELECT COUNT(*) AS count FROM events';
		$stm = $this->bd->prepare ($sql);
		$stm->execute ();
		$res = $stm->fetchAll (PDO::FETCH_ASSOC);

		return $res[0]['count'];
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
			$list[$key]->_id ($dao['idEvent']);
			$list[$key]->_title ($dao['title']);
			$list[$key]->_author ($dao['author']);
			$list[$key]->_date ($dao['date']);
			$list[$key]->_place ($dao['place']);
			$list[$key]->_description ($dao['description']);
			$list[$key]->_expirationdate ($dao['expirationdate']);
		}

		return $list;
	}
}
