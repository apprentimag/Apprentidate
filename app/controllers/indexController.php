<?php

class indexController extends ActionController {
	public function firstAction () {
	}
	
	public function indexAction () {
		View::prependTitle ('Accueil - ');
	}
}
