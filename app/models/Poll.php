<?php

class Poll extends Model {
	private $id;
	private $idEvent;
	private $title;
	private $expirationdate;
	private $choices = array ();
	private $voters = array ();
	private $adminpass;
	
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
	public function expirationdate () {
		return $this->expirationdate;
	}
	public function choices () {
		return $this->choices;
	}
	public function voters () {
		return $this->voters;
	}
	public function adminpass () {
		return $this->adminpass;
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
	public function _expirationdate ($value) {
		$this->expirationdate = $value;
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
	public function _adminpass($value) {
		$this->adminpass = $value;
	}
}

class PollDAO extends Model_pdo {
	
	public function addPoll ($values) {
		$sql = 'INSERT INTO polls (idPoll, idEvent, adminpass, expirationdate, title) VALUES(?, ?, ?, ?, ?)';
		$stm = $this->bd->prepare ($sql);

		$choices = $values['choices'];
		$id_poll = generateUniqueID();
		
		$values = array (
			$id_poll,
			$values['idEvent'],
			hashAdminPass($id_poll, $values['adminpass']),
			$values['expirationdate'],
			$values['title'],
		);

		if ($stm && $stm->execute ($values)) {
			$sql = 'INSERT INTO choices (idPoll, choice) VALUES(?, ?)';
			$stm = $this->bd->prepare ($sql);
			foreach ($choices as $choice) {
				if(!$stm->execute (array($id_poll, $choice)))
					return false;
			}
			return $id_poll;
		} else {
			return false;
		}
	}
	
	public function updatePoll ($id, $values) {
		$sql = 'UPDATE polls SET expirationdate=?, title=? WHERE idPoll=?';
		$stm = $this->bd->prepare ($sql);

		$values = array (
			$values['expirationdate'],
			$values['title'],
			$id
		);

		if ($stm && $stm->execute ($values)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function vote ($id, $values) {
		$sql = 'INSERT INTO results (choice, idPoll, name) VALUES(?, ?, ?)';
		$stm = $this->bd->prepare ($sql);
		$voter = $values['voter'];
		$choices = "";
		foreach($values['choices'] as $choice) {
			$choices = $choices . "$choice,";
		}
		$values = array($choices, $id, $voter);
		if ($stm->execute ($values)) {
			return true;
		} else {
			return false;
		}	
	}
	
	public function deletePoll ($id) {
		$sql = 'DELETE FROM events WHERE idPoll=?';
		$stm = $this->bd->prepare ($sql);

		$values = array ($id);

		if ($stm && $stm->execute ($values)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function listPolls () {
		$sql = 'SELECT * FROM polls ORDER BY title';
		$stm = $this->bd->prepare ($sql);
		$stm->execute ();

		return HelperPoll::daoToPoll ($stm->fetchAll (PDO::FETCH_ASSOC));
	}
	
	public function listByEventId ($id) {
		$sql = 'SELECT * FROM polls WHERE idEvent=?';
		$stm = $this->bd->prepare ($sql);
		$stm->execute (array($id));
		$polls = HelperPoll::daoToPoll ($stm->fetchAll (PDO::FETCH_ASSOC));
		foreach ($polls as $poll) {
			$sql = 'SELECT * FROM results WHERE idPoll=?';
			$stm = $this->bd->prepare ($sql);
			$stm->execute (array($poll->id ()));
			$res = $stm->fetchAll (PDO::FETCH_ASSOC);
			$values = HelperResults::daoToResults($res);
			$poll->_voters($values);
		}
		
		return $polls;
	}
	
	public function searchById ($id) {
		//TODO check if id exists
		$sql = 'SELECT * FROM polls WHERE idPoll=?';
		$stm = $this->bd->prepare ($sql);
		$stm->execute (array($id));

		$res = $stm->fetchAll (PDO::FETCH_ASSOC);
		$values = HelperPoll::daoToPoll ($res);
		$poll = $values[0];

		if (isset ($poll)) {
				$sql = 'SELECT * FROM choices WHERE idPoll=?';
				$stm = $this->bd->prepare ($sql);
				$stm->execute (array($id));
				$res = $stm->fetchAll (PDO::FETCH_ASSOC);

				$values = HelperChoices::daoToChoices($res);
				$poll->_choices($values);
				
				$sql = 'SELECT * FROM results WHERE idPoll=?';
				$stm = $this->bd->prepare ($sql);
				$stm->execute (array($id));
				$res = $stm->fetchAll (PDO::FETCH_ASSOC);
				$values = HelperResults::daoToResults($res);
				$poll->_voters($values);
			return $poll;
		} else {
			return false;
		}
	}
	
	
	public function count () {
		$sql = 'SELECT COUNT(*) AS count FROM polls';
		$stm = $this->bd->prepare ($sql);
		$stm->execute ();
		$res = $stm->fetchAll (PDO::FETCH_ASSOC);

		return $res[0]['count'];
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
			$list[$key]->_id ($dao['idPoll']);
			$list[$key]->_idEvent ($dao['idEvent']);
			$list[$key]->_title ($dao['title']);
			$list[$key]->_expirationdate ($dao['expirationdate']);
			$list[$key]->_adminpass ($dao['adminpass']);
		}

		return $list;
	}
}

class HelperChoices {
	public static function daoToChoices ($listDAO) {
		$list = array ();

		if (!is_array ($listDAO)) {
			$listDAO = array ($listDAO);
		}

		foreach ($listDAO as $key => $dao) {
			$list[$dao['idChoice']] = $dao['choice'];
		}

		return $list;
	}
}

class HelperResults {
	public static function daoToResults ($listDAO) {
		$list = array ();

		if (!is_array ($listDAO)) {
			$listDAO = array ($listDAO);
		}

		foreach ($listDAO as $key => $dao) {
			$choices = explode(",", $dao['choice']);
			$list[$dao['name']] = $choices;
		}

		return $list;
	}
}
