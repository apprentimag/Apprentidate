<?php

class Comment extends Model {
	private $id;
	private $id_event;
	private $author;
	private $date;
	private $content;
	
	public function __construct () { }
	
	public function id () {
		return $this->id;
	}
	public function idEvent () {
		return $this->id_event;
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
	public function content ($raw = false) {
		if ($raw) {
			return $this->content;
		} else {
			return nl2br ($this->content);
		}
	}
	
	public function _id ($id) {
		$this->id = $id;
	}
	public function _idEvent ($id) {
		$this->id_event = $id;
	}
	public function _author ($value) {
		$this->author = $value;
	}
	public function _date ($value) {
		$this->date = $value;
	}
	public function _content ($value) {
		$this->content = $value;
	}
}

class CommentDAO extends Model_array {
	public function __construct () {
		parent::__construct (PUBLIC_PATH . '/data/comments');
	}
	
	public function addComment ($values) {
		$id = $values['date'];
		
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
	
	public function updateComment ($id, $values) {
		foreach ($values as $key => $value) {
			$this->array[$id][$key] = $value;
		}
		
		$this->writeFile($this->array);
	}
	
	public function deleteComment ($id) {
		unset ($this->array[$id]);
		$this->writeFile($this->array);
	}
	
	public function listByEventId ($id) {
		$list = array ();
		
		foreach ($this->array as $key => $comm) {
			if ($comm['idEvent'] == $id) {
				$list[$key] = $comm;
			}
		}
		
		return HelperComment::daoToComment ($list);
	}
	
	public function searchById ($id) {
		$list = HelperComment::daoToComment ($this->array);
		
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

class HelperComment {
	public static function daoToComment ($listDAO) {
		$list = array ();

		if (!is_array ($listDAO)) {
			$listDAO = array ($listDAO);
		}

		foreach ($listDAO as $key => $dao) {
			$list[$key] = new Comment ();
			$list[$key]->_id ($key);
			$list[$key]->_idEvent ($dao['idEvent']);
			$list[$key]->_author ($dao['author']);
			$list[$key]->_date ($dao['date']);
			$list[$key]->_content ($dao['content']);
		}

		return $list;
	}
}
