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

class CommentDAO extends Model_pdo {
	
	public function addComment ($values) {
		$sql = 'INSERT INTO comments (idEvent, author, date, content) VALUES(?, ?, ?, ?)';
		$stm = $this->bd->prepare ($sql);

		$values = array (
			$values['idEvent'],
			$values['author'],
			$values['date'],
			$values['content'],
		);

		if ($stm && $stm->execute ($values)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function updateComment ($id, $values) {
		$sql = 'UPDATE comments SET author=?, date=?, content=? WHERE idComment=?';
		$stm = $this->bd->prepare ($sql);

		$values = array (
			$values['author'],
			$values['date'],
			$values['content'],
			$id
		);

		if ($stm && $stm->execute ($values)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function deleteComment ($id) {
		$sql = 'DELETE comments WHERE idComment=?';
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
		$sql = 'SELECT * FROM comments WHERE idEvent=? ORDER BY date';
		$stm = $this->bd->prepare ($sql);
		
		$values = array (
			$id
		);
		
		$stm->execute ($values);

		return HelperComment::daoToComment ($stm->fetchAll (PDO::FETCH_ASSOC));
	}
	
	public function searchById ($id) {
		$sql = 'SELECT * FROM comments WHERE idComment=?';
		$stm = $this->bd->prepare ($sql);

		$values = array ($id);

		$stm->execute ($values);
		$res = $stm->fetchAll (PDO::FETCH_ASSOC);
		$values = HelperComment::daoToComment ($res);

		if (isset ($values[0])) {
			return $values[0];
		} else {
			return false;
		}
	}
	
	public function count () {
		$sql = 'SELECT COUNT(*) AS count FROM comments';
		$stm = $this->bd->prepare ($sql);
		$stm->execute ();
		$res = $stm->fetchAll (PDO::FETCH_ASSOC);

		return $res[0]['count'];
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
			$list[$key]->_id ($dao['idComment']);
			$list[$key]->_idEvent ($dao['idEvent']);
			$list[$key]->_author ($dao['author']);
			$list[$key]->_date ($dao['date']);
			$list[$key]->_content ($dao['content']);
		}

		return $list;
	}
}
