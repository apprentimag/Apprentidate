<?php

class indexController extends ActionController {
	public function firstAction () {
	}
	
	public function indexAction () {
		View::appendTitle (' - Préparons nos évènements !');
		
		$eventDAO = new EventDAO ();
		$this->view->nbEvents = $eventDAO->count ();
	}
	
	public function loginAction () {
		$this->view->_useLayout (false);
		
		$url = 'https://verifier.login.persona.org/verify';
		$assert = Request::param ('assertion');
		$params = 'assertion=' . $assert . '&audience=' .
		          urlencode (Url::display () . ':80');
		$ch = curl_init ();
		$options = array (
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_POST => 2,
			CURLOPT_POSTFIELDS => $params
		);
		curl_setopt_array ($ch, $options);
		$result = curl_exec ($ch);
		curl_close ($ch);
		
		$res = json_decode ($result, true);
		if ($res['status'] == 'okay') {
			Session::_param ('mail', $res['email']);
		}
		
		echo $result;
	}
	
	public function logoutAction () {
		$this->view->_useLayout (false);
		Session::_param ('mail');
	}
}
