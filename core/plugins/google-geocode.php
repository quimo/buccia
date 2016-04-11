<?php 
	class GoogleGeocode {
	
		const GOOGLE_APIKEY = 'myGoogleAPIkey';
		const SOURCEDIR = 'source';
		const TARGETDIR = 'target';
		const FIELD_SEPARATOR = ';';
		const REQUEST_DELAY = 2;
	
		private $google_apikey = '';
		private $google_geocoder_url = 'https://maps.googleapis.com/maps/api/geocode/xml';
		private $sourcefile_path;
		private $targetfile_path;
		private $data = array();
		private $row = 0;
		private $lat;
		private $lng;
		private $status = '';
		private $filename;
		private $address_fields = array();
	
		public function __construct() {
			$this->sourcefile_path = dirname(__FILE__).'/'.GoogleGeocode::SOURCEDIR;
			$this->targetfile_path = dirname(__FILE__).'/'.GoogleGeocode::TARGETDIR;
			$this->row = 0;
		}
		
		/*
			getSourceFile
			recupera il file CSV sorgente
		*/
		
		public function getSourceFile($filename,$address_fields) {
			$this->data = file($this->sourcefile_path.'/'.$filename.'.csv');
			$this->address_fields = $address_fields;
			$this->filename = $filename;
			return $this->data;
		}
		
		/*
			geocodeThis
			interroga Google con un indirizzo e ritorna lo status code
		*/
		
		public function geocodeThis($address) {
			$address = str_replace(' ','+',$address);
			$response = file_get_contents($this->google_geocoder_url.'?address='.$address.'&key='.GoogleGeocode::GOOGLE_APIKEY);
			$xml = simplexml_load_string($response);
			//leggo l'esito dell'operazione di geododing
			$status = $xml->status;
			switch($status) {
				case 'OK':
					//indicates that no errors occurred; the address was successfully parsed and at least one geocode was returned
					$this->lat = $xml->result->geometry->location->lat;
					$this->lng = $xml->result->geometry->location->lng;
					break;
				case 'ZERO_RESULTS':
					// indicates that the geocode was successful but returned no results. This may occur if the geocoder was passed a non-existent address
					$this->lat = 0;
					$this->lng = 0;
					break;
				case 'OVER_QUERY_LIMIT':	
					//indicates that you are over your quota
					$this->lat = 0;
					$this->lng = 0;
					break;
				case 'REQUEST_DENIED':
					//indicates that your request was denied
					$this->lat = 0;
					$this->lng = 0;
					break;
				case 'INVALID_REQUEST':
					//generally indicates that the query (address, components or latlng) is missing
					$this->lat = 0;
					$this->lng = 0;
					break;
				case 'UNKNOWN_ERROR':
					//indicates that the request could not be processed due to a server error. The request may succeed if you try again
					$this->lat = 0;
					$this->lng = 0;
			}
			if ($status != OK) $status = $status.'_ERROR';
			$this->status = $status;
			return $status;
		}
		
		public function getCSVFields() {
			return $this->getLat().GoogleGeocode::FIELD_SEPARATOR.$this->getLng().GoogleGeocode::FIELD_SEPARATOR.$this->status;
		}
		
		public function getLat() {
			return $this->lat;
		}
		
		public function getLng() {
			return $this->lng;
		}
		
		/*
			geocode
			percorre il CSV ed esegue il geocoding
		*/
		
		public function geocode($handle) {
			if ($this->data) {
				for ($i = 0; $i < count($this->data); $i++) {
					$j = $i+1;
					$address = $this->getAddressFromCSV($this->data[$i]);
					$this->geocodeThis($address);
					$fields = $this->getCSVFields();
					$this->data[$i] = str_replace("\r\n",'',$this->data[$i]);
					$this->data[$i] = $j.GoogleGeocode::FIELD_SEPARATOR.$this->data[$i];
					$this->data[$i] .= GoogleGeocode::FIELD_SEPARATOR.$fields."\r\n";
					fwrite($handle,$this->data[$i]);
					sleep(GoogleGeocode::REQUEST_DELAY);
				}			
			}
			return false;
		}
		
		public function start() {
			$handle = fopen($this->targetfile_path.'/'.$this->filename.'_'.date("Y-m-d").'_'.date("H-i").'.csv','w');
			if ($handle) {
				$this->geocode($handle);
				fclose($handle);
			}	
		}
			
		public function getAddressFromCSV($CSVrow) {
			$fields = explode(';',$CSVrow);
			//compongo la stringa dell'indirizzo
			$address = '';
			for ($i = 0; $i < count ($this->address_fields); $i++) {
				$address .= $fields[$this->address_fields[$i]].' ';
			}
			return substr($address,0,-1);
		}
		
	
	}
?>	