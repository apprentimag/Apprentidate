<?php

class Guest extends Model {
	private $id;
	private $id_event;
	private $name;
	
	public function __construct () { }
	
	public function id () {
		return $this->id;
	}
	public function idEvent () {
		return $this->id_event;
	}
	
	public function name ($raw = false) {
		return $this->name;
	}

	public function _id ($id) {
		$this->id = $id;
	}
	public function _idEvent ($id) {
		$this->id_event = $id;
	}
	public function _name ($value) {
		$this->name = $value;
	}
}

class GuestDAO extends Model_pdo {
	
	public function addGuest ($values) {
		$sql = 'INSERT INTO guests (idEvent, name) VALUES(?, ?)';
		$stm = $this->bd->prepare ($sql);

		$values = array (
			$values['idEvent'],
			$values['name'],
		);

		if ($stm && $stm->execute ($values)) {
			return true;
		} else {
			return false;
		}
	}
	
	
	public function deleteGuest ($id) {
		$sql = 'DELETE comments WHERE idGuest=?';
		$stm = $this->bd->prepare ($sql);

		$values = array (
			$id
		);

		if ($stm && $stm->execute ($values)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function listByEventId ($id) {
		$sql = 'SELECT * FROM guests WHERE idEvent=? ORDER BY name';
		$stm = $this->bd->prepare ($sql);
		
		$values = array (
			$id
		);
		
		$stm->execute ($values);

		return HelperGuest::daoToGuest ($stm->fetchAll (PDO::FETCH_ASSOC));
	}
	
	
	public function count () {
		$sql = 'SELECT COUNT(*) AS count FROM guests';
		$stm = $this->bd->prepare ($sql);
		$stm->execute ();
		$res = $stm->fetchAll (PDO::FETCH_ASSOC);

		return $res[0]['count'];
	}
}

class HelperGuest {
	public static function daoToGuest ($listDAO) {
		$list = array ();

		if (!is_array ($listDAO)) {
			$listDAO = array ($listDAO);
		}

		foreach ($listDAO as $key => $dao) {
			$list[$key] = new Guest ();
			$list[$key]->_id ($dao['idGuest']);
			$list[$key]->_idEvent ($dao['idEvent']);
			$list[$key]->_name ($dao['name']);
		}

		return $list;
	}
}
