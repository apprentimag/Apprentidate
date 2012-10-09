<?php
/** 
 * MINZ - Copyright 2011 Marien Fressinaud
 * Sous licence AGPL3 <http://www.gnu.org/licenses/>
*/
require ('FrontController.php');

class App_FrontController extends FrontController {
	public function init () {
		$this->loadLibs ();
		$this->loadModels ();
		$this->loadScriptsAndStyles ();
		
		Session::init ();
	}
	
	private function loadLibs () {
		require (LIB_PATH . '/lib_event.php');
	}
	
	private function loadModels () {
		include (APP_PATH . '/models/Event.php');
		include (APP_PATH . '/models/Comment.php');
		include (APP_PATH . '/models/Poll.php');
	}
	
	private function loadScriptsAndStyles () {
		View::appendStyle (Url::display ('/theme/base.css'));
		View::appendScript ('https://login.persona.org/include.js');
		View::appendScript (Url::display ('/scripts/jquery.js'));
	}
}
