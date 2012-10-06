<?php

/**
 * La classe Session gère la session utilisateur
 * C'est un singleton
 */
class Session {
	/**
	 * $session stocke les variables de session
	 */
	private static $session = array ();
	
	/**
	 * Initialise la session
	 */
	public static function init () {
		// démarre la session
		session_name (md5 (Configuration::selApplication ()));
		session_start ();
		
		if (isset ($_SESSION)) {
			self::$session = $_SESSION;
		}
	}
	
	
	/**
	 * Permet de récupérer une variable de session
	 * @param $p le paramètre à récupérer
	 * @return la valeur de la variable de session, false si n'existe pas
	 */
	public static function param ($p, $default = false) {
		if (isset (self::$session[$p])) {
			$return = self::$session[$p];
		} else {
			$return = $default;
		}
		
		return $return;
	}
	
	
	/**
	 * Permet de créer ou mettre à jour une variable de session
	 * @param $p le paramètre à créer ou modifier
	 * @param $v la valeur à attribuer, false pour supprimer
	 */
	public static function _param ($p, $v = false) {
		if ($v === false) {
			unset ($_SESSION[$p]);
			unset (self::$session[$p]);
		} else {
			$_SESSION[$p] = $v;
			self::$session[$p] = $v;
		}
	}
	
	
	/**
	 * Permet d'effacer une session
	 * @param $force si à false, n'efface pas le paramètre de langue
	 */
	public static function unset_session ($force = false) {
		$language = self::param ('language');
		
		session_unset ();
		self::$session = array ();
		
		if (!$force) {
			self::_param ('language', $language);
		}
	}
}
