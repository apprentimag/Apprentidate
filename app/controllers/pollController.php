<?php

class pollController extends ActionController {
	public function seeAction () {
		$id = htmlspecialchars (Request::param ('id'));
		
		$pollDAO = new PollDAO ();
		$this->view->poll = $pollDAO->searchById ($id);
		
		if ($this->view->poll === false) {
			Error::error (
				404,
				array ('error' => array ('La page que vous cherchez n\'existe pas'))
			);
		} else {
			$eventDAO = new EventDAO ();
			$this->view->event = $eventDAO->searchById ($this->view->poll->idEvent ());
			
			if ($this->view->event === false) {
				Error::error (
					404,
					array ('error' => array ('La page que vous cherchez n\'existe pas'))
				);
			}
		}
	}

	public function createAction () {
		$id = htmlspecialchars (Request::param ('id'));
		
		$eventDAO = new EventDAO ();
		$this->view->event = $eventDAO->searchById ($id);
		
		if ($this->view->event === false) {
			Error::error (
				404,
				array ('error' => array ('La page que vous cherchez n\'existe pas'))
			);
		} else {
			View::prependTitle ('Ajouter un sondage à ' . $this->view->event->title () . ' - ');
			
			$this->view->missing = array ();
			
			if (Request::isPost ()) {
				$title = trim (str_replace (' ', ' ', Request::param ('title')));
				$choices = trim (str_replace (' ', ' ', Request::param ('choices', '')));
			
				$required = array (
					'title' => $title,
					'choices' => $choices
				);
				$this->view->missing = check_missing ($required);
				
				// gère les choix
				$choices = preg_replace ('#(.+)(\n\s\n)(.+)#', "\\1\n\\3", $choices);
				$array_choices = explode ("\n", $choices);
			
				$values = array (
					'title' => htmlspecialchars ($title),
					'choices' => $array_choices,
					'idEvent' => $id,
					'voters' => array ()
				);
			
				if (empty ($this->view->missing)) {
					$pollDAO = new PollDAO ();
				
					$idPoll = $pollDAO->addPoll ($values);
				
					if ($idPoll !== false) {
						Request::forward (array (
							'c' => 'event',
							'a' => 'see',
							'params' => array ('id' => $id)
						), true);
					} else {
						$this->view->error = true;
					}
				} else {
					$this->view->values = array (
						'title' => $title,
						'choices' => $choices
					);
				}
			}
		}
	}
	
	public function voteAction () {
		$id = htmlspecialchars (Request::param ('id'));
		$voter = Request::param ('voter');
		$choices = Request::param ('choices');
		
		if ($voter != false) {
			$pollDAO = new PollDAO ();
			$values = array (
				'voter' => $voter,
				'choices' => $choices
			);
			
			$pollDAO->vote ($id, $values);
		}
		
		Request::forward (array (
			'c' => 'poll',
			'a' => 'see',
			'params' => array ('id' => $id)
		), true);
	}
}
