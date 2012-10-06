<?php
/** 
 * MINZ - Copyright 2011 Marien Fressinaud
 * Sous licence AGPL3 <http://www.gnu.org/licenses/>
*/
require ('FrontController.php');

class App_FrontController extends FrontController {
	public function init () {
		$this->loadModels ();
		$this->loadScriptsAndStyles ();
		
		Session::init ();
	}
	
	private function loadModels () {
	}
	
	private function loadScriptsAndStyles () {
		View::appendStyle (Url::display ('/theme/base.css'));
	}
}
