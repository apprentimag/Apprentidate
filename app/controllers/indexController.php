<?php

class indexController extends ActionController {
	public function firstAction () {
	}
	
	public function indexAction () {
		View::appendTitle (' - Préparons nos évènements !');
		
		$eventDAO = new EventDAO ();
		$this->view->nbEvents = $eventDAO->count ();
		$pollDAO = new PollDAO ();
		$this->view->nbPolls = $pollDAO->count ();
	}

	public function aboutAction () {
	}
}
