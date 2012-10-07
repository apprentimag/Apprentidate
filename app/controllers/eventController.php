<?php

class eventController extends ActionController {
	public function firstAction () {
	}
	
	public function createAction () {
		View::prependTitle ('Créer un évènement - ');
		
		$this->view->missing = array ();
		
		if (Request::isPost ()) {
			$title = trim (str_replace (' ', ' ', Request::param ('title')));
			$author = trim (str_replace (' ', ' ', Request::param ('author')));
			$date = trim (str_replace (' ', ' ', Request::param ('date')));
			$place = trim (str_replace (' ', ' ', Request::param ('place', '')));
			$desc = trim (str_replace (' ', ' ', Request::param ('description', '')));
			
			$required = array (
				'title' => $title,
				'author' => $author,
				'date' => $date
			);
			$this->view->missing = check_missing ($required);
			
			$values = array (
				'title' => htmlspecialchars ($title),
				'author' => $author,
				'dates' => array (strtotime ($date)),
				'place' => htmlspecialchars ($place),
				'description' => htmlspecialchars ($desc),
				'participants' => array ($author)
			);
			
			if (empty ($this->view->missing)) {
				$eventDAO = new EventDAO ();
				
				$id = $eventDAO->addEvent ($values);
				
				if ($id !== false) {
					Request::forward (array (
						'c' => 'event',
						'a' => 'see',
						'params' => array ('id' => $id)
					), true);
				} else {
					$this->view->error = true;
				}
			} else {
				$this->view->values = $values;
			}
		}
	}
	
	public function seeAction () {
		$id = htmlspecialchars (Request::param ('id'));
		
		$eventDAO = new EventDAO ();
		$this->view->event = $eventDAO->searchById ($id);
		
		if ($this->view->event === false) {
			Error::error (
				404,
				array ('error' => array ('La page que vous cherchez n\'existe pas'))
			);
		}
		
		$comDAO = new CommentDAO ();
		$this->view->commentaires = array_reverse ($comDAO->listByEventId ($id));
	}
	
	public function add_userAction () {
		$id = htmlspecialchars (Request::param ('id'));
		$user = trim (str_replace (' ', ' ', Request::param ('user')));
		
		if ($id != false && $user != false) {
			$eventDAO = new EventDAO ();
			$event = $eventDAO->searchById ($id);
			$parts = $event->participants (true);
			$parts[] = $user;
			
			$values = array (
				'participants' => $parts
			);
			
			$eventDAO->updateEvent ($id, $values);
		}
		
		Request::forward (array (
			'c' => 'event',
			'a' => 'see',
			'params' => array ('id' => $id)
		), true);
	}
	
	public function delete_userAction () {
		$idEvent = htmlspecialchars (Request::param ('idEvent'));
		$id = Request::param ('id');
		
		if ($idEvent != false && $id !== false) {
			$eventDAO = new EventDAO ();
			$event = $eventDAO->searchById ($idEvent);
			$parts = $event->participants (true);
			
			unset ($parts[$id]);
			
			$values = array (
				'participants' => $parts
			);
			
			$eventDAO->updateEvent ($idEvent, $values);
		}
		
		Request::forward (array (
			'c' => 'event',
			'a' => 'see',
			'params' => array ('id' => $idEvent)
		), true);
	}
	
	public function add_commentAction () {
		$id = htmlspecialchars (Request::param ('id'));
		$user = trim (str_replace (' ', ' ', Request::param ('user')));
		$content = trim (str_replace (' ', ' ', htmlspecialchars (Request::param ('content'))));
		
		if ($user != false && $content != false) {
			$commentDAO = new CommentDAO ();
			
			$values = array (
				'idEvent' => $id,
				'author' => $user,
				'date' => time (),
				'content' => $content
			);
			
			$commentDAO->addComment ($values);
		}
		
		Request::forward (array (
			'c' => 'event',
			'a' => 'see',
			'params' => array ('id' => $id)
		), true);
	}
	
	public function delete_commentAction () {
		$id = htmlspecialchars (Request::param ('id'));
		$idEvent = htmlspecialchars (Request::param ('idEvent'));
		
		if ($id != false) {
			$commentDAO = new CommentDAO ();
			$commentDAO->deleteComment ($id);
		}
		
		Request::forward (array (
			'c' => 'event',
			'a' => 'see',
			'params' => array ('id' => $idEvent)
		), true);
	}
}
