<?php
	function _t($key,$lang) {
		
		$translation = array(
			'it' => array(
				'email_reset_password_subject' => "Reimposta la tua password",
				'email_reset_password_message' => "Gentile Socio,<br><br>ricevi questo messaggio in seguito alla richiesta di una nuova password.<br><br>Clicca questo link per la sua reimpostazione:<br>[+code+]<br><br><strong>Se invece NON hai richiesto alcun cambio password ignora semplicemente questa email.</strong><br><br>A presto.<br><em>TREE</em>",
				'email_reset_password_anchor_text' => "Reimposta la tua password"
			),
			'en' => array()
		);
		
		return $translation[$lang][$key];
	}
?>