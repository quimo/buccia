<?php
	session_start();
	
	include '../config.php';
	include '../buccia.php';
	include 'login.php';
	include '../plugin/helpers.php';
	include '../plugin/translate.php';
	include '../plugin/phpmailer/class.phpmailer.php';

	switch($_POST['action']) {
		
		/* logout */
		case 'logout':
			$login = new Login();
			//eseguo il logout
			$login->logout();
			break;
		
		/* invio messaggio di reset password */
		case 'remember':
			$login = new Login();
			//cerco email utente
			if ($login->checkEmail($_POST['email'])) {
				//invio messaggio
				$subject = _t('email_reset_password_subject','it');
				$message = _t('email_reset_password_message','it');
				$reset_password_anchor_text = _t('email_reset_password_anchor_text','it');
				$login->sendRememberPasswordMessage($subject,$message,$_POST['email'],$reset_password_anchor_text);
				echo json_encode(array("result"=>true));
			} else {
				echo json_encode(array("result"=>false));
			}
			break;
	}
?>