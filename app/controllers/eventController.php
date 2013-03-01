<?php

class eventController extends ActionController {
	public function seeAction () {
		$id = htmlspecialchars (Request::param ('id'));
		
		$eventDAO = new EventDAO ();
		$this->view->event = $eventDAO->searchById ($id);
		
		$guestDAO = new GuestDAO ();
		$this->view->guests = $guestDAO->listByEventId($id);
		
		if ($this->view->event === false) {
			Error::error (
				404,
				array ('error' => array ('La page que vous cherchez n\'existe pas'))
			);
		} else {
			View::prependTitle ($this->view->event->title () . ' - ');
		
			$comDAO = new CommentDAO ();
			$this->view->commentaires = array_reverse ($comDAO->listByEventId ($id));
			
			$pollDAO = new PollDAO ();
			$this->view->polls = $pollDAO->listByEventId ($id);
		}
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
			$expirationdate = trim (str_replace (' ', ' ', Request::param ('expirationdate')));
			$required = array (
				'title' => $title,
				'author' => $author,
				'date' => $date
			);
			$this->view->missing = check_missing ($required);
			
			$timestamp = strtotime ($date);
			if ($timestamp == false) {
				$this->view->missing[] = 'date';
			}
			
			$timestampexpiration = strtotime($expirationdate);
			if ($timestampexpiration == false) {
				$this->view->missing[] = 'expirationdate';
			}
				
			$values = array (
				'title' => htmlspecialchars ($title),
				'author' => $author,
				'date' => $timestamp,
				'place' => htmlspecialchars ($place),
				'description' => htmlspecialchars ($desc),
				'participants' => array ($author),
				'expirationdate' => $timestampexpiration
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
				$values['date'] = $date;
				$this->view->values = $values;
			}
		}
	}
	
	public function editAction () {
		$id = htmlspecialchars (Request::param ('id'));
		
		$eventDAO = new EventDAO ();
		$this->view->event = $eventDAO->searchById ($id);
		
		if ($this->view->event === false) {
			Error::error (
				404,
				array ('error' => array ('La page que vous cherchez n\'existe pas'))
			);
		} else {
			$this->view->missing = array ();
		
			$author = $this->view->event->author ();
			if (!isset ($author['mail']) || (is_logged () && $author['mail'] == Session::param ('mail'))) {
				View::prependTitle ('Éditer `' . $this->view->event->title () . '` - ');
			
				if (Request::isPost ()) {
					$title = trim (str_replace (' ', ' ', Request::param ('title')));
					$author = trim (str_replace (' ', ' ', Request::param ('author')));
					$date = trim (str_replace (' ', ' ', Request::param ('date')));
					$place = trim (str_replace (' ', ' ', Request::param ('place', '')));
					$desc = trim (str_replace (' ', ' ', Request::param ('description', '')));
					$expirationdate = trim (str_replace (' ', ' ', Request::param ('expirationdate')));
				
					$required = array (
						'title' => $title,
						'author' => $author,
						'date' => $date
					);
					$this->view->missing = check_missing ($required);
				
					$timestamp = strtotime ($date);
					if ($timestamp == false) {
						$this->view->missing[] = 'date';
					}
				
					$timestampexpiration = strtotime($expirationdate);
					if ($timestampexpiration == false) {
						$this->view->missing[] = 'expirationdate';
					}
				
					$values = array (
						'title' => htmlspecialchars ($title),
						'author' => $author,
						'date' => $timestamp,
						'place' => htmlspecialchars ($place),
						'description' => htmlspecialchars ($desc),
						'participants' => array ($author),
						'expirationdate' => $timestampexpiration
					);
			
					if (empty ($this->view->missing)) {
						$eventDAO = new EventDAO ();
				
						$eventDAO->updateEvent ($id, $values);
					
						Request::forward (array (
							'c' => 'event',
							'a' => 'see',
							'params' => array ('id' => $id)
						), true);
					}
				}
			} else {
				Error::error (
					403,
					array ('error' => array ('Vous n\'avez pas le droit d\'accéder à cette page'))
				);
			}
		}
	}
	
	public function add_userAction () {
		$id = htmlspecialchars (Request::param ('id'));
		$user = trim (str_replace (' ', ' ', Request::param ('user')));
		
		if ($id != false && $user != false) {
			$guestDAO = new GuestDAO ();
			$guestDAO->addGuest(array (
			'idEvent' => $id,
			'name' => $user
			));
		}
		
		Request::forward (array (
			'c' => 'event',
			'a' => 'see',
			'params' => array ('id' => $id)
		), true);
	}
	
	public function delete_userAction () {
		$idEvent = Request::param ('idEvent');
		$id = Request::param ('id');
		
		if (is_logged() && $id != false) {
			$guestDAO = new GuestDAO ();
			$guestDAO->deleteGuest ($id);
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
			'params' => array ('id' => $id),
			'anchor' => 'commentaires'
		), true);
	}
	
	public function delete_commentAction () {
		$idEvent = htmlspecialchars (Request::param ('idEvent'));
		
		if (is_logged ()) {
			$id = htmlspecialchars (Request::param ('id'));
		
			if ($id != false) {
				$commentDAO = new CommentDAO ();
				$commentDAO->deleteComment ($id);
			}
		}
		
		Request::forward (array (
			'c' => 'event',
			'a' => 'see',
			'params' => array ('id' => $idEvent)
		), true);
	}
}
