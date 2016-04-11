<?php
	class Helpers {
		
		static function normalizeBrandname($name) {
			return strtolower(str_replace(' ','-',$name));
		}
		
		static function renderSelectOptions($data, $selected = '', $default = '-', $class='') {
			$dummy = ($class) ? " class=\"$class first\"" : 'class="first"';
			$html = ($default !== false) ? "<option value=\"\"$dummy>$default</option>" : '';
			$i = 0;
			foreach ($data as $key => $value) {
				$dummy = ($class) ? "class=\"$class" : '';
				if ($i == 0 && !$default) $dummy .= " first";
				if ($i == 0 && !$default && !$class) $dummy = "class=\"first\"";
				if ($i == count($data)-1 && $class) $dummy .= " last";
				if ($i == count($data)-1 && !$class) $dummy = "class=\"last\"";
				$dummy .= ($class) ? "\"" : '';
				$html .= "<option $dummy value=\"$key\"";
				if ($selected) {
					if ($selected == $key) $html .= ' selected="selected" ';	
				}	
				$html .= ">$value</option>";
				$i++;
			}
			return $html;
		}
		
		static function renderSelectMultioptions($data, $selected = '', $default = '-', $class='') {
			$selected = (is_array($selected)) ? $selected : explode(',',$selected);
			$dummy = ($class) ? " class=\"$class first\"" : 'class="first"';
			$html = ($default !== false) ? "<option value=\"\"$dummy>$default</option>" : '';
			$i = 0;
			foreach ($data as $key => $value) {
				$dummy = ($class) ? "class=\"$class" : '';
				if ($i == 0 && !$default) $dummy .= " first";
				if ($i == 0 && !$default && !$class) $dummy = "class=\"first\"";
				if ($i == count($data)-1 && $class) $dummy .= " last";
				if ($i == count($data)-1 && !$class) $dummy = "class=\"last\"";
				$dummy .= ($class) ? "\"" : '';
				$html .= "<option $dummy value=\"$key\"";
				if (in_array($key,$selected)) $html .= ' selected="selected" ';	
				$html .= ">$value</option>";
				$i++;
			}
			return $html;
		}
		
		static function renderSelectOptiongroups($data, $selected = '', $default = '-', $class='') {
			$dummy = ($class) ? " class=\"$class first\"" : 'class="first"';
			$html = ($default !== false) ? "<option value=\"\"$dummy>$default</option>" : '';
			$i = 0;
			foreach($data as $key => $value) {
				$html .= "<optgroup label=\"$key\">";
				foreach ($value as $key2 => $value2) {
					$dummy = ($class) ? "class=\"$class" : '';
					if ($i == 0 && !$default) $dummy .= " first";
					if ($i == 0 && !$default && !$class) $dummy = "class=\"first\"";
					if ($i == count($value)-1 && $class) $dummy .= " last";
					if ($i == count($value)-1 && !$class) $dummy = "class=\"last\"";
					$dummy .= ($class) ? "\"" : '';
					$html .= "<option $dummy value=\"$key2\"";
					if ($selected == $key2) $html .= ' selected="selected" ';	
					$html .= ">$value2</option>";
					$i++;
				}
				$html .= "</optgroup>";
			}	
				
			
			return $html;
		}
		
		static function getStato($id) {
			$stati = self::getStati();
			return $stati[$id];
		}
		
		static function getStati() {
			return array(
				1 => 'Afghanistan',
				'Albania',
				'Algeria',
				'Andorra',
				'Angola',
				'Antigua e Barbuda',
				'Arabia Sa,udita',
				'Argentina',
				'Armenia',
				'Australia',
				'Austria',
				'Azerbaijan',
				'Bahamas',
				'Bahrein',
				'Bangladesh',
				'Barbados',
				'Belgio',
				'Belize',
				'Benin',
				'Bhutan',
				'Bielorussia',
				'Birmania',
				'Bolivia',
				'Bosnia-Herzegovina',
				'Botswana',
				'Brasile',
				'Brunei',
				'Bulgaria',
				'Burkina Faso',
				'Burundi',
				'Cambogia',
				'Camerun',
				'Canada',
				'Capo Verde',
				'Ciad',
				'Cile',
				'Cina (Rep. Pop.)',
				'Cipro',
				'Citt&agrave; del Vaticano',
				'Colombia',
				'Comore',
				'Corea',
				'Corea (Rep. Pop. Dem.)',
				'Costa d\',Avorio',
				'Costa Rica',
				'Croazia',
				'Cuba',
				'Danimarca',
				'Dominica',
				'Ecuador',
				'Egitto',
				'El Salvador',
				'Emirati Arabi Uniti',
				'Eritrea',
				'Estonia',
				'Etiopia',
				'Federazione Russa',
				'Fiji',
				'Filippine',
				'Finlandia',
				'Francia',
				'Gabon',
				'Gambia',
				'Georgia',
				'Germania',
				'Ghana',
				'Giamaica',
				'Giappone',
				'Gibuti',
				'Giordania',
				'Grecia',
				'Grenada',
				'Guadeloupe',
				'Guatemala',
				'Guinea',
				'Guinea Equatoriale',
				'Guinea-Bissau',
				'Guyana',
				'Haiti',
				'Honduras',
				'India',
				'Indonesia',
				'Iran (Rep. Isl.)',
				'Iraq',
				'Irlanda',
				'Islanda',
				'Isole Marshall',
				'Isole Salomone',
				'Israele',
				'Italia',
				'Kazakhstan',
				'Kenya',
				'Kirghizistan',
				'Kiribati',
				'Kuwait',
				'Laos',
				'Lesotho',
				'Lettonia',
				'Libano',
				'Liberia',
				'Libia',
				'Liechtenstein',
				'Lituania',
				'Lussemburgo',
				'Madagascar',
				'Malawi',
				'Maldive',
				'Malesia',
				'Mali',
				'Malta',
				'Marocco',
				'Martinique',
				'Mauritania',
				'Mauritius',
				'Messico',
				'Micronesia',
				'Moldavia',
				'Monaco',
				'Mongolia',
				'Montenegro',
				'Mozambico',
				'Namibia',
				'Nauru',
				'Nepal',
				'Nicaragua',
				'Niger',
				'Nigeria',
				'Norvegia',
				'Nuova Caledonia',
				'Nuova Zelanda',
				'Olanda',
				'Oman',
				'Pakistan',
				'Palau',
				'Panama',
				'Papua Nuova Guinea',
				'Paraguay',
				'Peru',
				'Polinesia',
				'Polonia',
				'Porto Rico',
				'Portogallo',
				'Qatar',
				'Regno Unito',
				'Repubblica Ceca',
				'Repubblica Centrafricana',
				'Repubblica del Congo',
				'Repubblica di Macedonia',
				'Republica Dominicana',
				'Romania',
				'Ruanda',
				'Sahara Occidentale',
				'Saint Kitts e Nevis',
				'Saint Vincent e Grenadine',
				'Samoa',
				'San Marino',
				'Santa Lucia',
				'São Tomé e Príncipe',
				'Senegal',
				'Serbia',
				'Seychelles',
				'Sierra Leone',
				'Singapore',
				'Siria',
				'Slovacchia',
				'Slovenia',
				'Somalia',
				'Somaliland',
				'Spagna',
				'Sri Lanka',
				'Stati Uniti',
				'Sudafrica',
				'Sudan',
				'Suriname',
				'Svezia',
				'Svizzera',
				'Swaziland',
				'Tagikistan',
				'Taiwan',
				'Tanzania',
				'Thailandia',
				'Timor Est',
				'Togo',
				'Tokelau',
				'Tonga',
				'Trinidad e Tobago',
				'Tunisia',
				'Turchia',
				'Turkmenistan',
				'Tuvalu',
				'Ucraina',
				'Uganda',
				'Ungheria',
				'Uruguay',
				'Uzbekistan',
				'Vanuatu',
				'Venezuela',
				'Vietnam',
				'Yemen',
				'Zaire',
				'Zambia',
				'Zimbabwe',
				'Altro'
			);
		}
		
		static function getRegioni() {
			return array(
				1 => 'Abruzzo',
				'Basilicata',
				'Calabria',
				'Campania',
				'Emilia-Romagna',
				'Friuli-Venezia Giulia',
				'Lazio',
				'Liguria',
				'Lombardia',
				'Marche',
				'Molise',
				'Piemonte',
				'Puglia',
				'Sardegna',
				'Sicilia',
				'Toscana',
				'Trentino-Alto Adige',
				'Umbria',
				'Val d\'Aosta',
				'Veneto'
			);
		}
		
		static function getProvinceByRegione($id) {
			switch($id) {
				case 1: 
				case 'Abruzzo';
					return array(
						1 => 'L\'Aquila',
						'Chieti',
						'Pescara',
						'Teramo'
					);
					break;
				case 2:
				case 'Basilicata':
					return array(
						5 => 'Matera',
						'Potenza'
					);
					break;
				case 3:
				case 'Calabria':
					return array(
						7 => 'Catanzaro',
						'Cosenza',
						'Crotone',
						'Reggio Calabria',
						'Vibo Valentia'
					);
					break;
				case 4:
				case 'Campania':
					return array(
						12 => 'Avellino',
						'Benevento',
						'Caserta',
						'Napoli',
						'Salerno'
					);
					break;
				case 5:
				case 'Emilia-Romagna':
					return array(
						17 => 'Bologna',
						'Ferrara',
						'Forl&igrave; - Cesena',
						'Modena',
						'Parma',
						'Piacenza',
						'Ravenna',
						'Reggio nell\'Emilia',
						'Rimini'
					);
					break;
				case 6:
				case 'Friuli-Venezia Giulia':
					return array(
						26 => 'Gorizia',
						'Pordenone',
						'Trieste',
						'Udine'
					);
					break;
				case 7:
				case 'Lazio':
					return array(
						30 => 'Frosinone',
						'Latina',
						'Rieti',
						'Roma',
						'Viterbo'
					);	
					break;
				case 8:
				case 'Liguria':
					return array(
						35 => 'Genova',
						'Imperia',
						'La Spezia',
						'Savona'
					);	
					break;
				case 9:
				case 'Lombardia':
					return array(
						39 => 'Bergamo',
						'Brescia',
						'Como',
						'Cremona',
						'Lecco',
						'Lodi',
						'Mantova',
						'Milano',
						'Monza - Brianza',
						'Pavia',
						'Sondrio',
						'Varese'
					);
					break;
				case 10:
				case 'Marche':
					return array(
						51 => 'Ancona',
						'Ascoli Piceno',
						'Fermo',
						'Macerata',
						'Pesaro - Urbino'
					);
					break;
				case 11:
				case 'Molise':
					return array(
						56 => 'Campobasso',
						'Isernia'
					);
					break;
				case 12:
				case 'Piemonte':
					return array(
						58 => 'Alessandria',
						'Asti',
						'Biella',
						'Cuneo',
						'Novara',
						'Torino',
						'Verbano - Cusio - Ossola',
						'Vercelli'
					);
					break;
				case 13:
				case 'Puglia':
					return array(
						66 => 'Bari',
						'Barletta - Andria - Trani',
						'Brindisi',
						'Foggia',
						'Lecce',
						'Taranto'
					);
					break;
				case 14:
				case 'Sardegna':
					return array(
						72 => 'Cagliari',
						'Carbonia - Iglesias',
						'Medio Campidano',
						'Nuoro',
						'Olbia - Tempio',
						'Obliastra',
						'Oristano',
						'Sassari'
					);
					break;
				case 15:
				case 'Sicilia':
					return array(
						80 => 'Agrigento',
						'Caltanissetta',
						'Catania',
						'Enna',
						'Messina',
						'Palermo',
						'Ragusa',
						'Siracusa',
						'Trapani'
					);
					break;
				case 16:
				case 'Toscana':
					return array(
						89 => 'Arezzo',
						'Firenze',
						'Grosseto',
						'Livorno',
						'Lucca',
						'Massa - Carrara',
						'Pisa',
						'Pistoia',
						'Prato',
						'Siena'
					);
					break;
				case 17:
				case 'Trentino - Alto Adige':
					return array(
						99 => 'Bolzano',
						'Trento'
					);
					break;
				case 18:
				case 'Umbria':
					return array(
						101 => 'Perugia',
						'Terni'
					);
					break;
				case 19:
				case 'Val d\'aosta':
					return array(
						103 => 'Aosta'
					);	
					break;
				case 20:	
				case 'Veneto':
					return array(
						104 => 'Belluno',
						'Padova',
						'Rovigo',
						'Treviso',
						'Venezia',
						'Verona',
						'Vicenza'
					);
					break;
			}
		}
		
		static function getProvinciaById($id) {
			$province = self::getProvinceId();
			if ($id) return $province[$id];
			return false;
		}
		
		static function getProvinceId() {
			return array(
				80 => 'Agrigento',
				58 => 'Alessandria',
            	51 => 'Ancona',
				103 => 'Aosta',
				52 => 'Ascoli Piceno',
				89 => 'Arezzo',
				59 => 'Asti',
				12 => 'Avellino',
				66 => 'Bari',
				67 => 'Barletta - Andria - Trani',
				104 => 'Belluno',
				13 => 'Benevento',
				39 => 'Bergamo',
				60 => 'Biella',
				17 => 'Bologna',
				99 => 'Bolzano',
				40 => 'Brescia',
				68 => 'Brindisi',
				72 => 'Cagliari',
				81 => 'Caltanisetta',
				56 => 'Campobasso',
				73 => 'Carbonia - Iglesias',
				14 => 'Caserta',
				82 => 'Catania',
				7 => 'Catanzaro',
				2 => 'Chieti',
				41 => 'Como',
				8 => 'Cosenza',
				42 => 'Cremona',
				9 => 'Crotone',
				61 => 'Cuneo',
				83 => 'Enna',
				53 => 'Fermo',
				18 => 'Ferrara',
				90 => 'Firenze',
				69 => 'Foggia',
				19 => 'Forl&igrave; - Cesena',
				30 => 'Frosinone',
				35 => 'Genova',
				26 => 'Gorizia',
				91 => 'Grosseto',
				36 => 'Imperia',
				57 => 'Isernia',
				1 => 'L\'Aquila',
				37 => 'La Spezia',
				31 => 'Latina',
				70 => 'Lecce',
				43 => 'Lecco',
				92 => 'Livorno',
				44 => 'Lodi',
				93 => 'Lucca',
				54 => 'Macerata',
				45 => 'Mantova',
				5 => 'Matera',
				74 => 'Medio Campidano',
				84 => 'Messina',
				46 => 'Milano',
				20 => 'Modena',
				15 => 'Napoli',
				94 => 'Massa - Carrara',
				47 => 'Monza - Brianza',
				62 => 'Novara',
				75 => 'Nuoro',
				76 => 'Olbia - Tempio',
				77 => 'Ogliastra',
				78 => 'Oristano',
				105 => 'Padova',
				85 => 'Palermo',
				21 => 'Parma',
				48 => 'Pavia',
				101 => 'Perugia',
				55 => 'Pesaro - Urbino',
				3 => 'Pescara',
				22 => 'Piacenza',
				95 => 'Pisa',
				96 => 'Pistoia',
				27 => 'Pordenone',
				6 => 'Potenza',
				97 => 'Prato',
				86 => 'Ragusa',
				24 => 'Ravenna',
				10 => 'Reggio Calabria',
				23 => 'Reggio Emilia',
				32 => 'Rieti',
				25 => 'Rimini',
				33 => 'Roma',
				106 => 'Rovigo',
				16 => 'Salerno',
				79 => 'Sassari',
				38 => 'Savona',
				98 => 'Siena',
				87 => 'Siracusa',
				49 => 'Sondrio',
				71 => 'Taranto',
				4 => 'Teramo',
				102 => 'Terni',
				63 => 'Torino',
				88 => 'Trapani',
				100 => 'Trento',
				107 => 'Treviso',
				28 => 'Trieste',
				29 => 'Udine',
				50 => 'Varese',
				108 => 'Venezia',
				64 => 'Verbano - Cusio - Ossola',
				65 => 'Vercelli',
				109 => 'Verona',
				11 => 'Vibo Valentia',
				110 => 'Vicenza',
				34 => 'Viterbo'				  
			);
		}
		
		static function getProvinceSigla() {
			return array(
				'AG' => 'Agrigento',
				'AL' => 'Alessandria',
            	'AN' => 'Ancona',
				'AO' => 'Aosta',
				'AR' => 'Arezzo',
				'AP' => 'Ascoli Piceno',
				'AT' => 'Asti',
				'AV' => 'Avellino',
				'BA' => 'Bari',
				'BT' => 'Barletta - Andria - Trani',
				'BL' => 'Belluno',
				'BN' => 'Benevento',
				'BG' => 'Bergamo',
				'BI' => 'Biella',
				'BO' => 'Bologna',
				'BZ' => 'Bolzano',
				'BS' => 'Brescia',
				'BR' => 'Brindisi',
				'CA' => 'Cagliari',
				'CL' => 'Caltanisetta',
				'CB' => 'Campobasso',
				'CI' => 'Carbonia - Iglesias',
				'CE' => 'Caserta',
				'CT' => 'Catania',
				'CZ' => 'Catanzaro',
				'CH' => 'Chieti',
				'CO' => 'Como',
				'CS' => 'Cosenza',
				'CR' => 'Cremona',
				'KR' => 'Crotone',
				'CN' => 'Cuneo',
				'EN' => 'Enna',
				'FM' => 'Fermo',
				'FE' => 'Ferrara',
				'FI' => 'Firenze',
				'FG' => 'Foggia',
				'FC' => 'Forl&igrave; - Cesena',
				'FR' => 'Frosinone',
				'GE' => 'Genova',
				'GO' => 'Gorizia',
				'GR' => 'Grosseto',
				'IM' => 'Imperia',
				'IS' => 'Isernia',
				'AQ' => 'L\'Aquila',
				'SP' => 'La Spezia',
				'LT' => 'Latina',
				'LE' => 'Lecce',
				'LC' => 'Lecco',
				'LI' => 'Livorno',
				'LO' => 'Lodi',
				'LU' => 'Lucca',
				'MC' => 'Macerata',
				'MN' => 'Mantova',
				'MS' => 'Massa - Carrara',
				'MT' => 'Matera',
				'VS' => 'Medio Campidano',
				'ME' => 'Messina',
				'MI' => 'Milano',
				'MO' => 'Modena',
				'MB' => 'Monza - Brianza',
				'NA' => 'Napoli',
				'NO' => 'Novara',
				'NU' => 'Nuoro',
				'OG' => 'Ogliastra',
				'OT' => 'Olbia - Tempio',
				'OR' => 'Oristano',
				'PD' => 'Padova',
				'PA' => 'Palermo',
				'PR' => 'Parma',
				'PV' => 'Pavia',
				'PG' => 'Perugia',
				'PU' => 'Pesaro - Urbino',
				'PS' => 'Pescara',
				'PC' => 'Piacenza',
				'PI' => 'Pisa',
				'PT' => 'Pistoia',
				'PN' => 'Pordenone',
				'PZ' => 'Potenza',
				'PO' => 'Prato',
				'RG' => 'Ragusa',
				'RA' => 'Ravenna',
				'RC' => 'Reggio Calabria',
				'RE' => 'Reggio Emilia',
				'RI' => 'Rieti',
				'RN' => 'Rimini',
				'RM' => 'Roma',
				'RO' => 'Rovigo',
				'SA' => 'Salerno',
				'SS' => 'Sassari',
				'SV' => 'Savona',
				'SI' => 'Siena',
				'SR' => 'Siracusa',
				'SO' => 'Sondrio',
				'TA' => 'Taranto',
				'TE' => 'Teramo',
				'TR' => 'Terni',
				'TO' => 'Torino',
				'TP' => 'Trapani',
				'TN' => 'Trento',
				'TV' => 'Treviso',
				'TS' => 'Trieste',
				'UD' => 'Udine',
				'VA' => 'Varese',
				'VE' => 'Venezia',
				'VB' => 'Verbano - Cusio - Ossola',
				'VC' => 'Vercelli',
				'VR' => 'Verona',
				'VV' => 'Vibo Valentia',
				'VI' => 'Vicenza',
				'VT' => 'Viterbo'				  
			);
		}
		
		static function getDaysOfWeek($mode = 0) {
			switch($mode) {
				case 0: return array(
					'lunedi' => 'Lunedi',
					'martedi' => 'Martedi',
					'mercoledi' => 'Mercoledi',
					'giovedi' => 'Giovedi',
					'venerdi' => 'Venerdi',
					'sabato' => 'Sabato',
					'domenica' => 'Domenica'
				);
				case 1: return array(
					'lun' => 'Lunedi',
					'mar' => 'Martedi',
					'mer' => 'Mercoledi',
					'gio' => 'Giovedi',
					'ven' => 'Venerdi',
					'sab' => 'Sabato',
					'dom' => 'Domenica'
				);
				case 2: return array(
					1 => 'Lunedi',
					2 => 'Martedi',
					3 => 'Mercoledi',
					4 => 'Giovedi',
					5 => 'Venerdi',
					6 => 'Sabato',
					7 => 'Domenica'
				);
			}
		}
		
		static function datetimeToItaTime($datetime) {
			$dummy = explode(' ',$datetime);
			$time = explode(':',$dummy[1]);
			return $time[0].':'.$time[1];
		}
		
		static function datetimeToItaDate($datetime) {
			$dummy = explode(' ',$datetime);
			$date = explode('-',$dummy[0]);
			return $date[2].'/'.$date[1].'/'.$date[0];
		}
		
		static function dateItaToDateTime($date) {
			$dummy = explode('/',$date);
			return $dummy[2].'-'.$dummy[1].'-'.$dummy[0].' 00:00:01';
		}	
		
		static function dateItaToDate($date) {
			$dummy = explode('/',$date);
			return $dummy[2].'-'.$dummy[1].'-'.$dummy[0];
		}
		
		/* dateIsInRange **************************************************************
			verifica se la data $date è in un intervallo specifico
			le date sono nella forma '22-10-2015 14:30:45'
		******************************************************************************/	
		
		static function dateIsInRange($start,$end,$date='') {
			$start = strtotime($start);
			$end = strtotime($end);
			if ($start > $end) {
				$dummy = $start;
				$start = $end;
				$end = $dummy;
			}
			$now = (!$date) ? strtotime('now') : strtotime($date);
			if ($now >= $start && $now <= $end) return true;
			return false;
		}
		
		static function datediff($tipo, $partenza, $fine)
		{
			switch ($tipo)
			{
				case "A" : $tipo = 365;
				break;
				case "M" : $tipo = (365 / 12);
				break;
				case "S" : $tipo = (365 / 52);
				break;
				case "G" : $tipo = 1;
				break;
			}
			$arr_partenza = explode("/", $partenza);
			$partenza_gg = $arr_partenza[0];
			$partenza_mm = $arr_partenza[1];
			$partenza_aa = $arr_partenza[2];
			$arr_fine = explode("/", $fine);
			$fine_gg = $arr_fine[0];
			$fine_mm = $arr_fine[1];
			$fine_aa = $arr_fine[2];
			$date_diff = mktime(12, 0, 0, $fine_mm, $fine_gg, $fine_aa) - mktime(12, 0, 0, $partenza_mm, $partenza_gg, $partenza_aa);
			$date_diff  = floor(($date_diff / 60 / 60 / 24) / $tipo);
			return $date_diff;
		}
		
		static function getDaysOfMonth($month) {
			$days = array(
				0,
				31,
				28,
				31,
				30,
				31,
				30,
				31,
				31,
				30,
				31,
				30,
				31
			);
			return $days[$month];
		}
		
		static function normalizePhone($number) {
			$number = preg_replace('/\s+/','',$number);
			$number = str_replace('-','',$number);
			$number = str_replace('/','',$number);
			$number = str_replace('+','00',$number);
			return $number;
		}
		
		/* getPostValue *************************************************************
			recupera un dato in post e se non esiste imposta un default
		****************************************************************************/
		static function getPostValue($field,$default_value='') {
			return (isset($_POST[$field])) ? $_POST[$field] : $default_value;
		}
		
		/* getGetValue **************************************************************
			recupera un dato in get e se non esiste imposta un default
		****************************************************************************/
		static function getGetValue($field,$default_value='') {
			return (isset($_GET[$field])) ? $_GET[$field] : $default_value;
		}
		
		/* sendEmail ****************************************************************
			invia una email
		****************************************************************************/
		static function sendEmail($from, $email_from, $email_to, $subject = '', $message = '', $header = '') {
			$header_ = "From: \"$from\" <$email_from>\r\n".
    		"Reply-To: $email_from\r\n".
    		"X-Mailer: PHP/".phpversion();
			$header_ .= "MIME-Version: 1.0\r\nContent-type: text/plain; charset=UTF-8\r\n";
  			mail($email_to, '=?UTF-8?B?'.base64_encode($subject).'?=', utf8_encode($message), $header_ . $header);	
		}

		/* arrayToKeyValuePairs *****************************************************
			trasforma un array a più dimensioni in un array chiave / valore
		****************************************************************************/		
		static function arrayToKeyValuePairs($data,$key_field,$value_field) {
			$dummy = array();
			for ($i = 0; $i < count($data); $i++) {
				$dummy[$data[$i][$key_field]] = $data[$i][$value_field];
			}
			return $dummy;
		}	
		
		/* getField ***************************************************************************
		   recupera una colonna di dati
		**************************************************************************************/
		static public function getField($data,$field) {
			if (isset($data) && !empty($data)) {
				$fields = array();
				for ($i = 0; $i < count($data); $i++) {
					$fields[$i] = $data[$i][$field];
				}
			} else $fields = false;	
			return $fields;
		}
		
		/* inArrayAndDelete *********************************************************
			cerca in $destination_array i valori in $tofind_array
			i valori trovati vengono rimossi dall'array e viene
			restituito un array ripulito dagli elementi trovati
		****************************************************************************/
		static function inArrayAndDelete($tofind_array,$destination_array) {
			$dummy = array();
			for ($i = 0; $i < count($utenti); $i++) {
				if (!in_array($destination_array[$i],$tofind_array)) $dummy[] = $destination_array[$i];
			}
			return $dummy;
		}
		
		/* inArrayAndInsert *********************************************************
			inserisce in $destination_array i valori in $tofind_array
			se questi non sono già presenti
		****************************************************************************/
		static function inArrayAndInsert($tofind_array,$destination_array) {
			for ($i = 0; $i < count($tofind_array); $i++) {
				if (!in_array($tofind_array[$i],$destination_array)) $destination_array[] = $tofind_array[$i];
			}
			return $destination_array;
		}
		
		static function truncate($string,$chars) {
			if ($chars < strlen($string)) return substr($string,0,$chars).'...';
			else return $string;
		}
		
		
		static function html2txt($string) {
			$search = array(
				'@<script[^>]*?>.*?</script>@si',				// Strip out javascript
				'@<[\/\!]*?[^<>]*?>@si',						// Strip out HTML tags
				'@<style[^>]*?>.*?</style>@siU',				// Strip style tags properly
				'@<![\s\S]*?--[ \t\n\r]*>@'						// Strip multi-line comments including CDATA
			);
			return preg_replace($search,'',$string);
		}


		/* makeUnique *****************************************************************
			genera una stringa casuale 
		******************************************************************************/	
		function makeUnique ($length = 16) {
			$salt       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ012345678';
			$len        = strlen($salt);
			$makepass   = '';
			mt_srand(10000000*(double)microtime());
			for ($i = 0; $i < $length; $i++) {
				$makepass .= $salt[mt_rand(0,$len - 1)];
			}
			return $makepass;
		}
		
		/* udate *****************************************************************
			ritorna una data con i microsecondi: echo udate('Y-m-d H:i:s.u');
		******************************************************************************/	
		
		function udate($format = 'u', $utimestamp = null) {
				if (is_null($utimestamp)) $utimestamp = microtime(true);
				$timestamp = floor($utimestamp);
				$milliseconds = round(($utimestamp - $timestamp) * 1000000);
				return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
			}
		
		/**
		 * Strip out (X)HTML tags and invisible content.  This function
		 * is useful as a prelude to tokenizing the visible text of a page
		 * for use in a search engine or spam detector/remover.
		 *
		 * Unlike PHP's built-in strip_tags() function, this function will
		 * remove invisible parts of a web page that normally should not be
		 * indexed or passed through a spam filter.  This includes style
		 * blocks, scripts, applets, embedded objects, and everything in the
		 * page header.
		 *
		 * In anticipation of tokenizing the visible text, this function
		 * detects (X)HTML block tags (such as divs, paragraphs, and table
		 * cells) and inserts a carriage return before each one.  This
		 * insures that after tags are removed, words before and after the
		 * tag are not erroneously joined into a single word.
		 *
		 * Parameters:
		 * 	text		the (X)HTML text to strip
		 *
		 * Return values:
		 * 	the stripped text
		 *
		 * See:
		 * 	http://nadeausoftware.com/articles/2007/09/php_tip_how_strip_html_tags_web_page
		 */
		function strip_html_tags( $text )
		{
			// PHP's strip_tags() function will remove tags, but it
			// doesn't remove scripts, styles, and other unwanted
			// invisible text between tags.  Also, as a prelude to
			// tokenizing the text, we need to insure that when
			// block-level tags (such as <p> or <div>) are removed,
			// neighboring words aren't joined.
			$text = preg_replace(
				array(
					// Remove invisible content
					'@<head[^>]*?>.*?</head>@siu',
					'@<style[^>]*?>.*?</style>@siu',
					'@<script[^>]*?.*?</script>@siu',
					'@<object[^>]*?.*?</object>@siu',
					'@<embed[^>]*?.*?</embed>@siu',
					'@<applet[^>]*?.*?</applet>@siu',
					'@<noframes[^>]*?.*?</noframes>@siu',
					'@<noscript[^>]*?.*?</noscript>@siu',
					'@<noembed[^>]*?.*?</noembed>@siu',

					// Add line breaks before & after blocks
					'@<((br)|(hr))@iu',
					'@</?((address)|(blockquote)|(center)|(del))@iu',
					'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
					'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
					'@</?((table)|(th)|(td)|(caption))@iu',
					'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
					'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
					'@</?((frameset)|(frame)|(iframe))@iu',
				),
				array(
					' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
					"\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
					"\n\$0", "\n\$0",
				),
				$text );

			// Remove all remaining tags and comments and return.
			return strip_tags( $text );
		}
		
		
    }
?>
