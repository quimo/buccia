<?php

/*

 .o8                                        o8o
"888                                        `"'
 888oooo.  oooo  oooo   .ooooo.   .ooooo.  oooo   .oooo.
 d88' `88b `888  `888  d88' `"Y8 d88' `"Y8 `888  `P  )88b
 888   888  888   888  888       888        888   .oP"888
 888   888  888   888  888   .o8 888   .o8  888  d8(  888
 `Y8bod8P'  `V88V"V8P' `Y8bod8P' `Y8bod8P' o888o `Y888""8o

*/

	class Config {

		/* impostazioni modificabili ******************************************/
		const BASE_FOLDER = '/stage/buccia/';
		const TEMPLATE_FOLDER = '/assets/templates/default'; //default
		//const TEMPLATE_FOLDER = '/wp-content/themes/gaia/inc/templates'; //wordpress
		/* impostazioni modificabili ******************************************/

		private static $initialized = false;

		private static $pars = array(			//parametri
			'platform_dev_author' => 'Simone Alati',
			'platform_dev_email' => 's.alati@zenzerocomunicazione.it',
			'app_name' => 'Appname v.1.0',
			'app_mail' => 'info@appname.it'
		);

		private static function init() {
			//percorsi di base
			self::$pars['baseurl'] = (Config::BASE_FOLDER) ? $_SERVER['HTTP_HOST'].'/'.Config::BASE_FOLDER.'/' : $_SERVER['HTTP_HOST'];
			self::$pars['baseurl'] = str_replace('//','/',self::$pars['baseurl']);
			self::$pars['baseurl'] = str_replace('//','/',self::$pars['baseurl']);
			self::$pars['baseurl'] = 'http://'.self::$pars['baseurl'];
			self::$pars['basepath'] = $_SERVER['DOCUMENT_ROOT'].'/'.Config::BASE_FOLDER.'/';
			self::$pars['basepath'] = str_replace('//','/',self::$pars['basepath']);
			//percorsi immagini
			self::$pars['templatesurl'] = self::$pars['baseurl'].Config::TEMPLATE_FOLDER.'/';
			self::$pars['templatesurl'] = str_replace('//','/',self::$pars['templatesurl']);
			self::$pars['templatespath'] = self::$pars['basepath'].Config::TEMPLATE_FOLDER.'/';
			self::$pars['templatespath'] = str_replace('//','/',self::$pars['templatespath']);
			//percorso libreria
			$dummy = pathinfo(__FILE__);
			self::$pars['libpath'] = $dummy['dirname'];
			self::$pars['libpath'] = str_replace('//','/',self::$pars['libpath']);
			//inizializzazione avvenuta
			self::$initialized = true;
		}

		/*
			COSTRUTTORE
			la clausola PRIVATE impedisce la chiamata del costruttore (dato che la classe è statica)
		*/
		private function __construct() {}

		/*
			get
			ritorna una variabile di configurazione
		*/
		public static function get($key) {
			if (!self::$initialized) self::init();
			return self::$pars[$key];
		}

	}
?>
