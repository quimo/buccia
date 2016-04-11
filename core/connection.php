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

	class Connection {
		
		/* 
			connByXML
			si connette al database con PDO recuperando i dati da un file XML
		*/
		static function xml($config_file='db') {
			$conn_path = dirname(__FILE__);
			$conn_path .= "/config/";
			$conn_path = str_replace('//','/',$conn_path);
			$xml = simplexml_load_file($conn_path.$config_file.'.xml');
			$host = $xml->host;
			$username = $xml->username;
			$database = $xml->database;
			$password = $xml->password;
			$charset = $xml->charset;
			return self::pars($host,$database,$username,$password);
		}
		
		/* 
			connByPars
			si connette al database con PDO
		*/
		static function pars($host='',$database='',$username='',$password='') {
			try {
				$dsn = "mysql:host=$host;dbname=$database";
				return new PDO(				
					$dsn,
					$username,
					$password,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
				);
			} catch (PDOException $e) {
				echo 'Errore di connessione: '.$e->getMessage();	
			}	
		}
	
	}
?>