<?php

class indexController extends ActionController {
	public function firstAction () {
	}
	
	public function indexAction () {
		View::prependTitle ('Accueil - ');
		
		$eventDAO = new EventDAO ();
		$this->view->nbEvents = $eventDAO->count ();
	}
}
