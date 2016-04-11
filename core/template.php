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
                                                           

	
	/* Buccia *************************************************************************
		Classe per la creazione di applicazioni
		L'applicazione si deve comporre con file php (uno per pagina) che
		hanno corrispondenti template (stesso nome con estensione html)
		nella cartella assets/templates/nomeapp.
		I template possono contenere chunk ([*name*] - frammenti di html)
		e placeholder ([+name+] - campi del database).
		La cartella assets/templates/nomeapp/chunks contiene i chunk.
		La cartella assets/templates/nomeapp/filters contiene il
		file functions.php che al suo interno ha le funzioni che
		eseguono modifiche ai dati estratti dal db prima del rendering.
		Ogni funzione deve avere lo stesso nome del template a cui
		deve fare da filtro.
	**********************************************************************************/

	class Template {
		
		private static $data;
		private static $placeholder;
		
		/* addPlaceholder *********************************************************************
			aggiunge un segnaposto alla pagina
		**************************************************************************************/
		public static function addPlaceholder($key, $value) {
			self::$placeholder[$key] = $value;
		}
		
		/* render *****************************************************************************
		   recupera il template e esegue le sostituzioni dei placeholder
		**************************************************************************************/
		public static function render($template='',$data=false) {
			
			//esegue le trasformazioni dei dati per prepararli allo specifico template
			if ($data) $data = self::formatData($data,$template);
			
			//recupera il template					
			$currentpage = basename($_SERVER['PHP_SELF']);
			$currentpage = explode('.',$currentpage);
			$template_name = ($template) ? $template : $currentpage[0];
			$template = @file_get_contents(Config::get('templatespath').$template_name.".html");
			if ($template === false) $template = file_get_contents(Config::get('templatespath').$template_name.".htm");
				
			//recupera i chunk
			preg_match_all("/\[\*([^\*]+)\*\]/",$template,$chunk);
			$chunks = array();
			foreach ($chunk[1] as $name) {
				$chunks[$name] = @file_get_contents(Config::get('templatespath').'chunks/'.$name.".html");
				if ($chunks[$name] === false) $chunk[$name] = file_get_contents(Config::get('templatespath').'chunks/'.$name.".htm");
			}
	
			//se ci sono chunk
			if (!empty($chunks)) {
				$ext_chunks = array();
				//li passa in rassegna
				foreach ($chunks as $chunkname => $chunkcontent) {
					//se ci sono dati estratti dal database
					if ($data) {
						$html = '';
						//per ogni chunk controlla se ci sono dei placeholder che corrispondono ai campi del database
						for ($i = 0; $i < count($data); $i++) {
							$original_chunk = $chunkcontent;
							$modified_chunk = $chunkcontent;
							foreach	($data[$i] as $key => $value) {
								//esegue le sostituzioni
								$modified_chunk = str_replace("[+".$key."+]",$value,$modified_chunk);
							}
							//se ha trovato dei campi duplica il chunk con i dati sostiuiti per ogni riga del recordset
							if (strcmp($modified_chunk,$original_chunk) == 0) $html = $original_chunk;
							else $html .= $modified_chunk;
						}
						$ext_chunks[$chunkname] = $html;
					} else {
						//se non ci sono dati estratti da database il chunk resta invariato
						$ext_chunks[$chunkname] = $chunkcontent;
					}	
				}
				//sostituisce i chunk nel template
				foreach ($ext_chunks as $key => $value) {
					$template = str_replace("[*$key*]",$value,$template);
				}
			} 
			
			//cerca le sostituzioni con i placeholder
			if ($data) {
				$html = '';
				for ($i = 0; $i < count($data); $i++) {
					$row = $template;
					foreach	($data[$i] as $key => $value) {
						//esegue le sostituzioni
						$row = str_replace("[+".$key."+]",$value,$row);
					}
					if ($row != $template) $html .= $row;
				}
				if ($html) $template = $html;
			}
			
			//sostituisce i placeholders
			if (isset(self::$placeholder)) {
				foreach (self::$placeholder as $key => $value) {
					$template = str_replace("[+$key+]",$value,$template);
				}
			}
			
			return $template;
		}
		
		/* formatData *************************************************************************
		   formatta i dati per una deteminata vista
		**************************************************************************************/
		private static function formatData($data,$template='') {
			include_once Config::get('basepath').Config::TEMPLATE_FOLDER.'/filters/filters.php';	
			//recupera il nome della pagina e crea il nome della funzione filtro
			$currentpage = ($template) ? $template : basename($_SERVER['PHP_SELF']);
			$currentpage = str_replace('-','_',$currentpage);
			$fn = explode('.',$currentpage);
			//se esiste la funzione filtro la esegue
			if (function_exists($fn[0])) {
				for ($i = 0; $i < count($data); $i++) {
					eval($fn[0].'($data[$i]);');
				}
			}
			return $data;
		}
			
		/* renderPagination *************************************************************************
		   restituisce la stringa per la paginazione
		*********************************************************************************************/
		public static function renderPagination($num, $step = 10, $resultset_string='<span>[+results+]</span> risultati - Pagina <span>[+page+]</span> di <span>[+pages+]</span>') {
			$num = ($num) ? $num : 0;
			$pages = ($num) ? ceil($num / $step) : 1;
			$pagename = basename($_SERVER['PHP_SELF']);
			$start = (isset($_GET['start'])) ? $_GET['start'] : 0;
			$page = ($start / $step) + 1;
			$resultset_string = str_replace('[+results+]',$num,$resultset_string);
			$resultset_string = str_replace('[+page+]',$page,$resultset_string);
			$resultset_string = str_replace('[+pages+]',$pages,$resultset_string);
			$html = '';
			$html .= "<div class=\"pagination\">";
			$html .= "<div class=\"resultset\">$resultset_string</div>";
			$html .= "<div class=\"pageset\">";
			$html .= "<ul>";
			//rimuove la paginazione
			$querystring = $_SERVER['QUERY_STRING'];
			$$querystring = preg_replace('/&amp;start=[0-9]+&amp;step=[0-9]+/','',$querystring);
			$querystring = preg_replace('/&amp;start=[0-9]+/','',$querystring);
			for ($i = 0; $i < $pages; $i++) {
				$j = $i+1;
				$start2 = $i*$step;
				
				//se sono a inizio o fine lista pagina mostro i link
				if ($i == 0 || $i == ($pages-1)) {
					
					if ($i == 0) {
						//link alla prima pagina
						$html .= "<li class=\"goto\"><a href=\"$pagename?$querystring&start=0&amp;step=$step\">&laquo;</a></li>";
					}
					
					if ($start2 == $start) $html .= "<li class=\"active\">$j</li>";
					else $html .= "<li><a href=\"$pagename?$querystring&start=$start2&amp;step=$step\">$j</a></li>";
					
					if ($i == ($pages-1)) {
						//link all'ultima pagina
						$last = ($step*($pages-1));
						$html .= "<li class=\"goto\"><a href=\"$pagename?$querystring&start=$last&amp;step=$step\">&raquo;</a></li>";
					}
					
				} else {
				
					$page2 = ($start2 / $step) + 1;
					//mostro i link alle altre pagine solo se sono in un range di 4 pagine attorno alla pagina in cui sono
					if ((($page2 + 3) == $page) || (($page2 + 2) == $page) || (($page2 + 1) == $page) || (($page2 - 1) == $page) || (($page2 - 2) == $page) || (($page2 - 3) == $page) || ($page2 == $page)) {
						if (($page2 + 3) == $page) $html .= "<li class=\"break\"> .. </li>";
						if ($start2 == $start) $html .= "<li class=\"active\">$j</li>";
						else $html .= "<li><a href=\"$pagename?$querystring&start=$start2&amp;step=$step\">$j</a></li>";
						if (($page2 - 3) == $page) $html .= "<li class=\"break\"> .. </li>";
					}
					
				}
				
			}
			$html .= "</ul>";
			$html .= "</div>";
			$html .= "</div>";
			return $html;
		}
		
	}
?>