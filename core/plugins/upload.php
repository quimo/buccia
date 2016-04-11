<?php
	
	class Upload {
	
		private $error = 0;
		private $maxdim = 5242880; //5Mb
		private $errors = array(
			'nessun errore',
			'errore di upload',
			'selezionare un file valido',
			'tipo di file non ammesso',
			'dimensioni del file eccessive',
			'errore nello spostamento del file'
		);
		private $exts = array(
		);
			
		public function getMaxfilesize() {
			return $this->maxdim;
		}
		
		public function getErrorString() {
			if ($this->error) return $this->errors[$this->error];
			return false;
		}
		
		public function getError() {
			return $this->error;
		}
		
		public function addExtensions($exts) {
			for ($i = 0; $i < count($exts); $i++) {
				$this->exts[] = $exts[$i];
			}
		}
		
		/* verify ---------------------------------------------------------------------- */
		/* verifica il corretto upload                                                   */
		/* ------------------------------------------------------------------------------*/
		public function verify($fieldname,$filepath,$filename_prefix='') {	
			//controlla se si è verificato un errore
			if ($_FILES[$fieldname]['error']) {
				$this->error = 1;
				return false;
			}
			//controlla se è stato selezionato un file valido
			if ($_FILES[$fieldname]['name'] == '') {
				$this->error = 2;
				return false;
			}
			//controlla l'estensione del file
			$dummy = explode(".",$_FILES[$fieldname]['name']);
			$fileext = $dummy[count($dummy)-1];
			if (!in_array($fileext,$this->exts)) {
				$this->error = 3;
				return false;
			}
			//controlla le dimensioni del file
			if ($_FILES[$fieldname]['size'] >= $this->maxdim) {
				$this->error = 4;
				return false;
			}
			//rinomina il file
			$filename = $_FILES[$fieldname]['name'];
			//pulisce il nome del file da caratteri "strani"
			$filename = preg_replace("/[^a-zA-Z0-9-_\.]/","",$filename);
			if ($filename_prefix) $filename = $filename_prefix."_".$filename;
				
			//controlla l'esistenza del file sul server e lo rinomina se esiste già	
			$i = 0;
			while (file_exists($filepath."/".$filename)) {
				$i++;
				$filenameparts = explode(".",$filename);
				$filename = $filenameparts[0];
				$fileext = $filenameparts[count($filenameparts)-1];
				$filenameoriginal = explode("--",$filename);
				$newfilename = $filenameoriginal[0]."--$i";
				$filename = $newfilename.".$fileext";
			}
			//esegue l'upload
			if (!move_uploaded_file($_FILES[$fieldname]['tmp_name'],$filepath."/".$filename)) {		
				$this->error = 5;
				return 0;		
			}
			return $filename;
		}
		
	}
?>