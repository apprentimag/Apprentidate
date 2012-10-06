<?php
/** 
 * MINZ - Copyright 2011 Marien Fressinaud
 * Sous licence AGPL3 <http://www.gnu.org/licenses/>
*/

/**
 * La classe Configuration permet de g�rer la configuration de l'application
 */
class Configuration {
	const CONF_PATH_NAME = '/configuration/application.ini';
	
	/**
	 * VERSION est la version actuelle de MINZ
	 */
	const VERSION = '1.1.0';
	
	/**
	 * valeurs possibles pour l'"environment"
	 * SILENT rend l'application muette (pas de log)
	 * PRODUCTION est recommand�e pour une appli en production
	 *			(log les erreurs critiques)
	 * DEVELOPMENT log toutes les erreurs
	 */
	const SILENT = 0;
	const PRODUCTION = 1;
	const DEVELOPMENT = 2;
	
	/**
	 * d�finition des variables de configuration
	 * $sel_application une cha�ne de caract�res al�atoires (obligatoire)
	 * $environment g�re le niveau d'affichage pour log et erreurs
	 * $use_url_rewriting indique si on utilise l'url_rewriting
	 * $base_url le chemin de base pour acc�der � l'application
	 * $title le nom de l'application
	 * $language la langue par d�faut de l'application
	 * $cacheEnabled permet de savoir si le cache doit �tre activ�
	 * $delayCache la limite de cache
	 * $db param�tres pour la base de donn�es (tableau)
	 *     - host le serveur de la base
	 *     - user nom d'utilisateur
	 *     - password mot de passe de l'utilisateur
	 *     - base le nom de la base de donn�es
	 */
	private static $sel_application = '';
	private static $environment = Configuration::PRODUCTION;
	private static $base_url = '';
	private static $use_url_rewriting = false;
	private static $title = '';
	private static $language = 'en';
	private static $cache_enabled = true;
	private static $delay_cache = 3600;
	
	private static $db = array (
		'host' => false,
		'user' => false,
		'password' => false,
		'base' => false
	);
	
	/*
	 * Getteurs
	 */
	public static function selApplication () {
		return self::$sel_application;
	}
	public static function environment () {
		return self::$environment;
	}
	public static function baseUrl () {
		return self::$base_url;
	}
	public static function useUrlRewriting () {
		return self::$use_url_rewriting;
	}
	public static function title () {
		return self::$title;
	}
	public static function language () {
		return self::$language;
	}
	public static function cacheEnabled () {
		return self::$cache_enabled;
	}
	public static function delayCache () {
		return self::$delay_cache;
	}
	public static function dataBase () {
		return self::$db;
	}
	
	/**
	 * Initialise les variables de configuration
	 * @exception FileNotExistException si le CONF_PATH_NAME n'existe pas
	 * @exception BadConfigurationException si CONF_PATH_NAME mal format�
	 */
	public static function init () {
		try {
			self::parseFile ();
		} catch (BadConfigurationException $e) {
			throw $e;
		} catch (FileNotExistException $e) {
			throw $e;
		}
	}
	
	/**
	 * Parse un fichier de configuration de type ".ini"
	 * @exception FileNotExistException si le CONF_PATH_NAME n'existe pas
	 * @exception BadConfigurationException si CONF_PATH_NAME mal format�
	 */
	private static function parseFile () {
		if (!file_exists (APP_PATH . self::CONF_PATH_NAME)) {
			throw new FileNotExistException (
				APP_PATH . self::CONF_PATH_NAME,
				MinzException::ERROR
			);
		}
		$ini_array = parse_ini_file (
			APP_PATH . self::CONF_PATH_NAME,
			true
		);
		
		// [general] est obligatoire
		if (!isset ($ini_array['general'])) {
			throw new BadConfigurationException (
				'[general]',
				MinzException::ERROR
			);
		}
		$general = $ini_array['general'];
		
		
		// sel_application est obligatoire
		if (!isset ($general['sel_application'])) {
			throw new BadConfigurationException (
				'sel_application',
				MinzException::ERROR
			);
		}
		self::$sel_application = $general['sel_application'];
		
		if (isset ($general['environment'])) {
			switch ($general['environment']) {
			case 'silent':
				self::$environment = Configuration::SILENT;
				break;
			case 'development':
				self::$environment = Configuration::DEVELOPMENT;
				break;
			case 'production':
				self::$environment = Configuration::PRODUCTION;
				break;
			default:
				throw new BadConfigurationException (
					'environment',
					MinzException::ERROR
				);
			}
			
		}
		if (isset ($general['base_url'])) {
			self::$base_url = $general['base_url'];
		}
		if (isset ($general['use_url_rewriting'])) {
			self::$use_url_rewriting = $general['use_url_rewriting'];
		}
		
		if (isset ($general['title'])) {
			self::$title = $general['title'];
		}
		if (isset ($general['language'])) {
			self::$language = $general['language'];
		}
		if (isset ($general['cache_enabled'])) {
			self::$cache_enabled = $general['cache_enabled'];
		}
		if (isset ($general['delay_cache'])) {
			self::$delay_cache = $general['delay_cache'];
		}
		
		// Base de donn�es
		$db = false;
		if (isset ($ini_array['db'])) {
			$db = $ini_array['db'];
		}
		if ($db) {
			if (!isset ($db['host'])) {
				throw new BadConfigurationException (
					'host',
					MinzException::ERROR
				);
			}
			if (!isset ($db['user'])) {
				throw new BadConfigurationException (
					'user',
					MinzException::ERROR
				);
			}
			if (!isset ($db['password'])) {
				throw new BadConfigurationException (
					'password',
					MinzException::ERROR
				);
			}
			if (!isset ($db['base'])) {
				throw new BadConfigurationException (
					'base',
					MinzException::ERROR
				);
			}
			
			self::$db['host'] = $db['host'];
			self::$db['user'] = $db['user'];
			self::$db['password'] = $db['password'];
			self::$db['base'] = $db['base'];
		}
	}
}
