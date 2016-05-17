<?php

	/*

	CREATE TABLE IF NOT EXISTS `utenti` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`session_id` char(32) DEFAULT NULL,
		`email` varchar(50) NOT NULL,
		`password` char(32) NOT NULL,
		`lost_password_code` char(32) DEFAULT NULL,
		`created_on` datetime NOT NULL,
		`last_login` datetime NOT NULL,
		`logins` int(11) NOT NULL,
		`state` tinyint(4) NOT NULL,
		`role` tinyint(4) NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

	INSERT INTO `utenti` (`id`, `session_id`, `email`, `password`, `lost_password_code`, `created_on`, `last_login`, `logins`, `state`, `role`) VALUES
(1, NULL, 's.alati@zenzerocomunicazione.it', '27babe82445c81d01defcbbac58a95f0', 'V6PAvceOGPbS682wHaGthL8YOaCwiGvm', '2015-05-29 00:00:00', '2015-05-29 00:00:00', 0, 1, 99);

	(la password è aaaaaaaa)

	CREATE TABLE IF NOT EXISTS `utenti_dettagli` (
		`id` int(11) NOT NULL,
		`nome` varchar(50) NOT NULL,
		`cognome` varchar(50) NOT NULL,
		`id_azienda` int(11) NOT NULL,
		`id_posizione` int(11) NOT NULL,
		`telefono_casa` varchar(50) DEFAULT NULL,
		`telefono_lavoro` varchar(50) DEFAULT NULL,
		`cellulare` varchar(50) NOT NULL,
		`email_altro` varchar(50) DEFAULT NULL,
		`web` varchar(255) DEFAULT NULL,
		`skype` varchar(255) DEFAULT NULL,
		`stato` int(11) DEFAULT NULL,
		`provincia` char(2) DEFAULT NULL,
		`cap` varchar(15) DEFAULT NULL,
		`citta` varchar(100) DEFAULT NULL,
		`indirizzo` varchar(255) DEFAULT NULL,
		`note` text,
		`foto` varchar(255) DEFAULT NULL,
		`data_creazione` datetime NOT NULL,
		`data_modifica` datetime NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	*/

	class Login extends Database {

		const USERS_TABLE = 'utenti';
		const USERS_DETAILS_TABLE = 'utenti_dettagli';
		const PASSWORD_SALT = 'quimosalt';

		const SMTP_EMAIL_SENDER = 'info@appname.it';
		const SMTP_ADMIN_EMAIL = 'smtp@mittente.it';
		const SMTP_ADMIN_EMAIL_FROM = 'Mittente srl';
		const SMTP_ADMIN_EMAIL_PASSWORD = 'password';
		const SMTP_HOST = 'mail.host.it';
		const RESET_PASSWORD_LANDING_PAGE = 'reset-password.php';
		const RESET_PASSWORD_LANDING_PAGE_MOBILE = 'reset-password_mobile.php';
		const SMTP_EMAIL_BCC = 's.alati@zenzerocomunicazione.it';

		private $config;
		private $data;
		private $reset_password_landing_url;
		private $reset_password_landing_url_mobile;

		public function __construct() {
			$conn = Connection::xml();
			$this->reset_password_landing_url = Config::get('baseurl').Login::RESET_PASSWORD_LANDING_PAGE;
			$this->reset_password_landing_url_mobile = Config::get('baseurl').Login::RESET_PASSWORD_LANDING_PAGE_MOBILE;
			$this->db = parent::__construct($conn);
		}

		/* getPasswordSalt *************************************************************
			ritorna il seme usato nella generazione dellla password
		*******************************************************************************/
        public static function getPasswordSalt() {
            return Login::PASSWORD_SALT;
        }

        /* cryptPassword ***************************************************************
			cripta una password
		*******************************************************************************/
        public static function cryptPassword($password) {
            return md5(Login::PASSWORD_SALT.$password);
        }

		/* check ***********************************************************************
			controlla la presenza di un utente
		*******************************************************************************/
		public function check($email, $password, $role = 99) {
			$email = Helpers::strip_html_tags($email);
			$password = Helpers::strip_html_tags($password);
			if (trim($email) == '' || trim($password) == '') return false;
			$id = $this->search(Login::USERS_TABLE, array(
				'email' => $email,
				'password' => md5(Login::PASSWORD_SALT.$password),
				'state' => 1,
				'role' => $role
			));
			if ($id !== false) {
				/* salva la sessione */
				$this->update(Login::USERS_TABLE, array('session_id' => session_id()), $id);
			}
			return $id;
		}

		/* checkAdmin ******************************************************************
			controlla la presenza di un admin
		*******************************************************************************/
		public function checkAdmin($email, $password) {
			$email = Helpers::strip_html_tags($email);
			$password = Helpers::strip_html_tags($password);
			if (trim($email) == '' || trim($password) == '') return false;
			$id = $this->search(Login::USERS_TABLE, array(
				'email' => $email,
				'password' => md5(Login::PASSWORD_SALT.$password),
				'state' => 1,
				'role' => 99
			));
			if ($id !== false) {
				/* salva la sessione */
				$this->update(Login::USERS_TABLE, array('session_id' => session_id()), $id);
			}
			return $id;
		}

		/* checkEmail *******************************************************************
			verifica l'esistenza della mail
		********************************************************************************/
		public function checkEmail($email) {
			$email = Helpers::strip_html_tags($email);
			if (trim($email) == '') return false;
			$id = $this->search(Login::USERS_TABLE, array(
				'email' => $email
			));
			if ($id !== false) {
				return true;
			}
			return false;
		}

		/* getAllMailAddress *******************************************************************
			recupera tutte le email degli utenti attivi in base al ruolo
		********************************************************************************/
		public function getAllMailAddress($role=0) {
			$role = ($role != 0) ? "AND role = $role" : "AND role > 0";
			$dummy = $this->query("SELECT email FROM ".Login::USERS_TABLE." WHERE visibility = 1 $role");
			$emails = array();
			if ($dummy) {
				for ($i = 0; $i < count($dummy); $i++) {
					$emails[] = $dummy[$i]['email'];
				}
				return $emails;
			}
			return false;
		}

		/* checkEmail *******************************************************************
			verifica l'esistenza di una doppia mail (id è l'identificativo del record corrente
		********************************************************************************/
		public function isDuplicatedEmail($id='',$email) {
			$dummy = $this->query("SELECT id FROM ".Login::USERS_TABLE." WHERE email = '$email'");
			if ($id) {
				if ($dummy !== false && $dummy[0]['id'] != $id) return 1;
				return 0;
			} else {
				if ($dummy !== false) return 1;
				return 0;
			}

		}

		public function sendMessage($subject,$message,$email) {
			/* imposto l'invio */
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->CharSet = "utf-8";
			$mail->Host = Login::SMTP_HOST;
			$mail->SMTPAuth = false;
			$mail->Port = 25;
			$mail->Username = Login::SMTP_ADMIN_EMAIL;
			$mail->Password = Login::SMTP_ADMIN_EMAIL_PASSWORD;
			$mail->SetFrom(Login::SMTP_EMAIL_SENDER, Login::SMTP_ADMIN_EMAIL_FROM);
			$mail->AddReplyTo(Login::SMTP_EMAIL_SENDER, Login::SMTP_ADMIN_EMAIL_FROM);
			$mail->From = Login::SMTP_EMAIL_SENDER;
			$mail->AddAddress($email);
			/* TEST ------------------------------------------- */
			//$mail->SMTPDebug = 2;
			//$mail->AddCC($this->get('email_tester'));
			/* TEST ------------------------------------------- */
			$mail->IsHTML(true);
			$mail->Subject  = $subject;
			$mail->Body = $message;
			if (!$mail->Send()) return false;
			return true;
		}

		/* sendRememberPasswordMessage **************************************************
			invia il messaggio di reset password (richiede PHPMailer)
		********************************************************************************/
		public function sendRememberPasswordMessage($subject,$message,$email,$reset_password_anchor_text) {
			/* preparo il link per il reset della password */
			$code = $this->setLostPasswordCode($email);
			if ($code) {
				$html_code = "<a href=\"".$this->reset_password_landing_url."?code=$code\">$reset_password_anchor_text</a>";
				/* sostituisco il placeholder [+code+] con il codice per il link */
				$message = str_replace('[+code+]',$html_code,$message);
				/* imposto l'invio */
				$mail = new PHPMailer();
				$mail->IsSMTP();
				$mail->CharSet = "utf-8";
				$mail->Host = Login::SMTP_HOST;
				$mail->SMTPAuth = false;
				$mail->Port = 25;
				$mail->Username = Login::SMTP_ADMIN_EMAIL;
				$mail->Password = Login::SMTP_ADMIN_EMAIL_PASSWORD;
				$mail->SetFrom(Login::SMTP_EMAIL_SENDER, Login::SMTP_ADMIN_EMAIL_FROM);
				$mail->AddReplyTo(Login::SMTP_EMAIL_SENDER, Login::SMTP_ADMIN_EMAIL_FROM);
				$mail->From = Login::SMTP_EMAIL_SENDER;
				$mail->AddAddress($email);
				/* TEST ------------------------------------------- */
				//$mail->SMTPDebug = 2;
				//$mail->AddCC($this->get('email_tester'));
				/* TEST ------------------------------------------- */
				$mail->IsHTML(true);
				$mail->Subject  = $subject;
				$mail->Body = $message;
				if (!$mail->Send()) return false;
				return true;
			}
			return false;
		}

		/* sendRememberPasswordMessageMobile **************************************************
			invia il messaggio di reset password nella versione mobile (richiede PHPMailer)
		********************************************************************************/
		public function sendRememberPasswordMessageMobile($subject,$message,$email,$reset_password_anchor_text) {
			/* preparo il link per il reset della password */
			$code = $this->setLostPasswordCode($email);
			if ($code) {
				$html_code = "<a href=\"".$this->reset_password_landing_url_mobile."?code=$code\">$reset_password_anchor_text</a>";
				/* sostituisco il placeholder [+code+] con il codice per il link */
				$message = str_replace('[+code+]',$html_code,$message);
				/* imposto l'invio */
				$mail = new PHPMailer();
				$mail->IsSMTP();
				$mail->CharSet = "utf-8";
				$mail->Host = Login::SMTP_HOST;
				$mail->SMTPAuth = false;
				$mail->Port = 25;
				$mail->Username = Login::SMTP_ADMIN_EMAIL;
				$mail->Password = Login::SMTP_ADMIN_EMAIL_PASSWORD;
				$mail->SetFrom(Login::SMTP_EMAIL_SENDER, Login::SMTP_ADMIN_EMAIL_FROM);
				$mail->AddReplyTo(Login::SMTP_EMAIL_SENDER, Login::SMTP_ADMIN_EMAIL_FROM);
				$mail->From = Login::SMTP_EMAIL_SENDER;
				$mail->AddAddress($email);
				/* TEST ------------------------------------------- */
				//$mail->SMTPDebug = 2;
				//$mail->AddCC($this->get('email_tester'));
				/* TEST ------------------------------------------- */
				$mail->IsHTML(true);
				$mail->Subject  = $subject;
				$mail->Body = $message;
				if (!$mail->Send()) return false;
				return true;
			}
			return false;
		}

		/* sendPasswordChangedMessage **************************************************
			invia il messaggio di password cambiata (richiede PHPMailer)
		********************************************************************************/
		public function sendPasswordChangedMessage($subject,$message,$email) {
			/* imposto l'invio */
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->CharSet = "utf-8";
			$mail->Host = Login::SMTP_HOST;
			$mail->SMTPAuth = false;
			$mail->Port = 25;
			$mail->Username = Login::SMTP_ADMIN_EMAIL;
			$mail->Password = Login::SMTP_ADMIN_EMAIL_PASSWORD;
			$mail->SetFrom(Login::SMTP_EMAIL_SENDER, Login::SMTP_ADMIN_EMAIL_FROM);
			$mail->AddReplyTo(Login::SMTP_EMAIL_SENDER, Login::SMTP_ADMIN_EMAIL_FROM);
			$mail->From = Login::SMTP_EMAIL_SENDER;
			$mail->AddAddress($email);
			/* TEST ------------------------------------------- */
			//$mail->SMTPDebug = 2;
			//$mail->AddCC($this->get('email_tester'));
			/* TEST ------------------------------------------- */
			$mail->IsHTML(true);
			$mail->Subject  = $subject;
			$mail->Body = $message;
			if (!$mail->Send()) return false;
			return true;
		}

		/* setLostPasswordCode **********************************************************
			imposta il codice per resettare la password
		********************************************************************************/
		private function setLostPasswordCode($email) {
			$id = $this->search(Login::USERS_TABLE, array(
				'email' => $email
			));
			if ($id !== false) {
				$random = md5(mt_rand());
				$query = "UPDATE ".Login::USERS_TABLE." SET lost_password_code = ? WHERE email = ".$this->db->quote($email)." LIMIT 1";
				$statement = $this->db->prepare($query);
				$statement->execute(array($random));
				return $random;
			}
			return false;
		}

		/* isValidLostPasswordCode ******************************************************
			controlla se un codice è ancora presente
		********************************************************************************/
		function isValidLostPasswordCode($code) {
			$id = $this->search(Login::USERS_TABLE, array(
				'lost_password_code' => $code
			));
			if ($id !== false) return true;
			return false;
		}

		/* resetPassword ****************************************************************
			aggiorna la password
		********************************************************************************/
		function resetPassword($lost_password_code,$new_password) {
			$new_password = Helpers::strip_html_tags($new_password);
			/* aggiorna la password */
			$query = "UPDATE ".Login::USERS_TABLE." SET password = ? WHERE lost_password_code = ? LIMIT 1";
			$statement = $this->db->prepare($query);
			$statement->execute(array(md5(Login::PASSWORD_SALT.$new_password),$lost_password_code));
			/* recupera l'id del record aggiornato */
			$id = $this->search(Login::USERS_TABLE, array(
				'lost_password_code' => $lost_password_code
			));
			/* elimina il codice di reset */
			$query = "UPDATE ".Login::USERS_TABLE." SET lost_password_code = ? WHERE id = $id LIMIT 1";
			$statement = $this->db->prepare($query);
			$statement->execute(array(null));
			return $id;
		}

		/* isLogged ********************************************************************
			controlla se un utente è loggato
		*******************************************************************************/
		function isLogged() {
			$id = $this->search(Login::USERS_TABLE, array(
				'session_id' => session_id()
			));
			if ($id) return true;
			return false;
		}

		/* isAdmin ********************************************************************
			controlla se un utente è admin
		*******************************************************************************/
		function isAdmin($id) {
			return $this->search(Login::USERS_TABLE,array('id' => $id, 'role' => 99));
		}

		/* loginExpired ****************************************************************
			controlla se sono passati n giorni dall'ultimo login
		*******************************************************************************/
		function loginExpired($days) {
			$now = date('d/m/Y');
			$logged = $this->getLoggedUser();
			if (Helpers::datediff('G', Helpers::dateTimeToItaDate($logged[0]['last_login']), $now) >= $days) return true;
			return false;
		}

		/* logout ***********************************************************************
			esegue il logout rimuovendo la sessione
		********************************************************************************/
		function logout() {
			$query = "UPDATE ".Login::USERS_TABLE." SET session_id = ? WHERE session_id = '".session_id()."'";
			$statement = $this->db->prepare($query);
			$statement->execute(array(''));
		}

		/* getLoggedUser *******************************************************************
			ritorna i dati dell'utente loggato
		***********************************************************************************/
		function getLoggedUser() {
			$this->data = $this->query("SELECT * FROM ".Login::USERS_TABLE." WHERE session_id = '".session_id()."' LIMIT 1");
			return $this->data;
		}

		/* getLoggedUserId *****************************************************************
			ritorna l'id dell'utente loggato
		***********************************************************************************/
		function getLoggedUserId() {
			$dummy = $this->getLoggedUser();
			return $dummy[0]['id'];
		}

		/* getUser *************************************************************************
			ritorna i dati di un utente
		***********************************************************************************/
		function getUser($id) {
			$this->data = $this->query("SELECT * FROM ".Login::USERS_TABLE." WHERE id = $id LIMIT 1");
			return $this->data;
		}

		function getUserByEmail($email) {
			$this->data = $this->query("SELECT * FROM ".Login::USERS_TABLE." WHERE email = '$email' LIMIT 1");
			return $this->data;
		}

		/* getUserDetails ******************************************************************
			ritorna i dati di un utente
		***********************************************************************************/
		function getUserDetails($id) {
			$this->data = $this->query("SELECT * FROM ".Login::USERS_DETAILS_TABLE." WHERE id = $id LIMIT 1");
			return $this->data;
		}

		/* updateLastLogin *****************************************************************
			aggiorna la data di ultimo login
		***********************************************************************************/
		function updateLastLogin() {
			$query = "UPDATE ".Login::USERS_TABLE." SET last_login = ?, logins = logins + 1 WHERE session_id = '".session_id()."'";
			$statement = $this->db->prepare($query);
			$statement->execute(array(date('Y-m-d H:i:s')));
		}

		/* sendCreatedAccountNotice ****************************************************
			invia il messaggio di creazione account (richiede PHPMailer)
		********************************************************************************/
		public function sendCreatedAccountNotice($subject,$message) {
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->CharSet = "utf-8";
			$mail->Host = Login::SMTP_HOST;
			$mail->SMTPAuth = false;
			$mail->Port = 25;
			$mail->Username = Login::SMTP_ADMIN_EMAIL;
			$mail->Password = Login::SMTP_ADMIN_EMAIL_PASSWORD;
			$mail->SetFrom(Login::SMTP_EMAIL_SENDER, Login::SMTP_ADMIN_EMAIL_FROM);
			$mail->AddReplyTo(Login::SMTP_EMAIL_SENDER, Login::SMTP_ADMIN_EMAIL_FROM);
			$mail->From = Login::SMTP_EMAIL_SENDER;
			$mail->AddAddress(Login::SMTP_EMAIL_BCC);
			/* TEST ------------------------------------------- */
			//$mail->SMTPDebug = 2;
			/* TEST ------------------------------------------- */
			$mail->IsHTML(true);
			$mail->Subject  = $subject;
			$mail->Body = $message;
			$mail->Send();

			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->CharSet = "utf-8";
			$mail->Host = Login::SMTP_HOST;
			$mail->SMTPAuth = false;
			$mail->Port = 25;
			$mail->Username = Login::SMTP_ADMIN_EMAIL;
			$mail->Password = Login::SMTP_ADMIN_EMAIL_PASSWORD;
			$mail->SetFrom(Login::SMTP_EMAIL_SENDER, Login::SMTP_ADMIN_EMAIL_FROM);
			$mail->AddReplyTo(Login::SMTP_EMAIL_SENDER, Login::SMTP_ADMIN_EMAIL_FROM);
			$mail->From = Login::SMTP_EMAIL_SENDER;
			$mail->AddAddress($this->get('email_tester'));
			/* TEST ------------------------------------------- */
			//$mail->SMTPDebug = 2;
			/* TEST ------------------------------------------- */
			$mail->IsHTML(true);
			$mail->Subject  = $subject;
			$mail->Body = $message;
			$mail->Send();
		}

		/* sendCreatedAccountMessage ****************************************************
			invia il messaggio di creazione account (richiede PHPMailer)
		********************************************************************************/
		public function sendCreatedAccountMessage($subject,$message,$email) {
			/* imposto l'invio */
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->CharSet = "utf-8";
			$mail->Host = Login::SMTP_HOST;
			$mail->SMTPAuth = false;
			$mail->Port = 25;
			$mail->Username = Login::SMTP_ADMIN_EMAIL;
			$mail->Password = Login::SMTP_ADMIN_EMAIL_PASSWORD;
			$mail->SetFrom(Login::SMTP_EMAIL_SENDER, Login::SMTP_ADMIN_EMAIL_FROM);
			$mail->AddReplyTo(Login::SMTP_EMAIL_SENDER, Login::SMTP_ADMIN_EMAIL_FROM);
			$mail->From = Login::SMTP_EMAIL_SENDER;
			$mail->AddAddress($email);
			/* TEST ------------------------------------------- */
			//$mail->SMTPDebug = 2;
			//$mail->AddAddress($this->get('email_tester'));
			/* TEST ------------------------------------------- */
			$mail->IsHTML(true);
			$mail->Subject  = $subject;
			$mail->Body = $message;
			if (!$mail->Send()) return false;
			return true;
		}

	}

?>
