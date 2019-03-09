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
		$this->initDB();

		Session::init ();
	}
	
	private function loadLibs () {
		require (LIB_PATH . '/lib_event.php');
	}
	
	private function loadModels () {
		include (APP_PATH . '/models/Event.php');
		include (APP_PATH . '/models/Comment.php');
		include (APP_PATH . '/models/Poll.php');
		include (APP_PATH . '/models/Guest.php');
		include (APP_PATH . '/models/Auth.php');
	}
	
	private function loadScriptsAndStyles () {
		View::appendStyle (Url::display ('/bootstrap/css/bootstrap.min.css'));
		View::appendStyle (Url::display ('/bootstrap/css/bootstrap-responsive.css'));
		View::appendStyle (Url::display ('/bootstrap/css/bootstrap-datetimepicker.min.css'));
		View::appendStyle (Url::display ('/theme/style.css'));
		View::appendScript ('https://login.persona.org/include.js');
		View::appendScript (Url::display ('/scripts/jquery.js'));
		View::appendScript (Url::display ('/bootstrap/js/bootstrap.min.js'));
		View::appendScript (Url::display ('/bootstrap/js/bootstrap-datetimepicker.min.js'));
	}

	private function initDB () {
		$pdo = new Model_pdo();
		if (!$pdo->isInitialized()) {
			$schemaFilename = ROOT_PATH . DIRECTORY_SEPARATOR . 'schema.sql';
			if (file_exists($schemaFilename)) {
				$schema = file_get_contents($schemaFilename);
				$pdo->init($schema);
			}
		}
	}
}
