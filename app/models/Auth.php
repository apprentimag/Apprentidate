<?php

class Auth extends Model {
	private $token;
	private $id;
	private $expirationdate;
	private $ip;
	public function __construct () {
	}
	
	public function token () {
		return $this->token;
	}
	public function id () {
		return $this->id;
	}
	public function expirationdate () {
		return $this->expirationdate;
	}
	public function ip () {
		return $this->ip;
	}
	
	public function _id ($id) {
		$this->id = $id;
	}
	public function _token ($token) {
		$this->token = $token;
	}
	public function _ip ($ip) {
		$this->ip = $ip;
	}
	public function _expirationdate ($value) {
		$this->expirationdate = $value;
	}
}

class AuthDAO extends Model_pdo {
	
	public function addAuth ($values) {
		$sql = 'INSERT INTO auth (token, id, expirationdate, ip) VALUES(?, ?, ?, ?)';
		$stm = $this->bd->prepare ($sql);
		
		$values = array (
			$values['token'],
			$values['id'],
			$values['expirationdate'],
			$values['ip'],
		);

		return ($stm && $stm->execute ($values));
	}
	
	public function deleteAuth ($token) {
		$sql = 'DELETE FROM auth WHERE token=?';
		$stm = $this->bd->prepare ($sql);

		$values = array ($token);

		if ($stm && $stm->execute ($token)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function deleteAuthByDate ($timestamp) {
		$sql = 'DELETE FROM auth WHERE expirationdate <=?';
		$stm = $this->bd->prepare ($sql);

		$values = array ($timestamp);

		if ($stm && $stm->execute ($values)) {
			return true;
		} else {
			return false;
		}
	}
		
	public function searchById ($token) {
		$sql = 'SELECT * FROM auth WHERE token=?';
		$stm = $this->bd->prepare ($sql);

		$values = array ($token);

		$stm->execute ($values);
		$res = $stm->fetchAll (PDO::FETCH_ASSOC);
		$values = HelperAuth::daoToAuth ($res);

		if (isset ($values[0])) {
			return $values[0];
		} else {
			return false;
		}
	}
}

class HelperAuth {
	public static function daoToAuth ($listDAO) {
		$list = array ();

		if (!is_array ($listDAO)) {
			$listDAO = array ($listDAO);
		}

		foreach ($listDAO as $key => $dao) {
			$list[$key] = new Auth ();
			$list[$key]->_token ($dao['token']);
			$list[$key]->_id ($dao['id']);
			$list[$key]->_expirationdate ($dao['expirationdate']);
			$list[$key]->_ip ($dao['ip']);
		}

		return $list;
	}
}
