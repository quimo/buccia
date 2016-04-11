<?php
	function utenti(&$data) {
		//global $demo; //istanza della classe Demo (core/claees) che estende Database
		$data['name'] = strtoupper($data['name']);
		//$data['category'] = $demo->myMethod($data['id']);
	}
?>