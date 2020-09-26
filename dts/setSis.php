<?php
/***************************************
SETA URL DA HOME
****************************************/

	function setHome(){
		echo BASE;
	}

/***************************************
GERAR URL AMIGÁVEL
****************************************/

	function setUri($string){
		# Outra forma:
		$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
		$b = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';
		$string = utf8_decode($string);
		$string = strtr($string, utf8_decode($a), $b);
	   	$string = strip_tags(trim($string)); 
		$string = str_replace(" ","-",$string); 
		$string = str_replace(array("=====","====","===","=="),"=",$string);
		return strtolower(utf8_encode($string));
		
		# Outra forma:
		# special accents
		# $a = array('À','Á','Â','Ã','Ä','Å', 'Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å', 'æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','Ð','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i', '?', '?','J','j','K','k','L','l','L','l','L','l','?','?','L','l','N','n','N','n','N','n','?','O','o','O','o','O','o', 'Œ', 'œ','R','r','R','r','R','r','S','s','S','s','S','s','Š','š','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Ÿ','Z','z','Z','z','Ž','ž','?','ƒ','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','?','?', '?', '?','?','?');
		# $b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');
		# return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/','/[ -]+/','/^-|-$/'),array('','-',''),str_replace($a,$b,$string)));
	}
	
/***************************************
INCLUE ARQUIVOS
****************************************/

	function setArq($nomeArquivo){
		if(file_exists($nomeArquivo.'.php')){
			include($nomeArquivo.'.php');
		}else{
			echo "Erro ao incluir ".$nomeArquivo.'.php, arquivo ou caminho não conferem!';
		}
	}

/***************************************
SOMA VISITAS
****************************************/

	function setViews($topicoId){
		$topicoId = mysql_real_escape_string($topicoId);
		$realArtigo = read('up_posts',"WHERE id = $topicoId");
		
		foreach($realArtigo as $artigo);
			$views = $artigo['visitas'];
			$views = $views +1;
			$dataViews = array(
				'visitas' => $views
			);
			update('up_posts',$dataViews,"id = '$topicoId'");
	}	

	
?>