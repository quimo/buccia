<?php
	include 'core/config/config.php';
	include 'core/connection.php';
	include 'core/database.php';
	include 'core/template.php';
	include 'core/plugins/helpers.php';
	include 'core/classes/demo.php';
	//include 'core/plugin/login/login.php';
	
	//recupero di due parametri dell'applicazione
	$app_name = Config::get('app_name');
	echo $app_name;
	echo "<br>";
	$baseurl = Config::get('baseurl');
	echo $baseurl;
	echo "<br>";
	
	//uso della classe Login
	$login = new Login();
	
	//uso di una classe specifica
	$demo = new Demo();
	//estrazione dati
	$data = $demo->getAllData();
	
	//visualizzazione dati
	//$template = new Template();
	echo Template::render('utenti',$data);
?>