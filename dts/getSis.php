<?php
/***************************************
GET HOME
****************************************/

	function getHome(){
		$url = $_GET['url'];
		$url = explode('/', $url);
		$url[0] = ($url[0] == NULL ? 'index' : $url[0]);
		
		if(file_exists('tpl/'.$url[0].'.php')){
			require_once('tpl/'.$url[0].'.php');
		}elseif(file_exists('tpl/'.$url[0].'/'.$url[1].'.php')){
			require_once('tpl/'.$url[0].'/'.$url[1].'.php');
		}else{
			require_once('tpl/404.php');
		}
	}
	
/***************************************
GET THUMB
****************************************/

	function getThumb($img, $titulo, $alt, $w, $h, $grupo = NULL, $dir = NULL, $link = NULL, $a = NULL){
		
		//TIPOS DE CORTE
		$a = ($a != null ? '&a='.$a : '');
		//c : position in the center (this is the default)
		//t : align top
		//tr : align top right
		//tl : align top left
		//b : align bottom
		//br : align bottom right
		//bl : align bottom left
		//l : align left
		//r : align right
		
		$grupo = ($grupo != NULL ? "[$grupo]" : "");
		$dir = ($dir != NULL ? "[$dir]" : "uploads");
		
		$verDir = explode('/',$_SERVER['PHP_SELF']);
		$urlDir = (in_array('admin',$verDir) ? '../' : '');
		
		if(file_exists($urlDir.$dir.'/'.$img)){
			if($link == ''){
				echo '
				      <a href="'.BASE.'/'.$dir.'/'.$img.'" rel="shadowbox'.$grupo.'" title="'.$titulo.'">
				         <img src="'.BASE.'/tim.php?src='.BASE.'/'.$dir.'/'.$img.'&w='.$w.'&h='.$h.'&zc=1&q=100'.$a.'" title="'.$titulo.'" alt="'.$alt.'">
				      </a>
				';
			}elseif($link == '#'){
				echo '
				      <img src="'.BASE.'/tim.php?src='.BASE.'/'.$dir.'/'.$img.'&w='.$w.'&h='.$h.'&zc=1&q=100'.$a.'" title="'.$titulo.'" alt="'.$alt.'">
				';
			}else{
				echo '
				      <a href="'.$link.'" title="'.$titulo.'">
				         <img src="'.BASE.'/tim.php?src='.BASE.'/'.$dir.'/'.$img.'&w='.$w.'&h='.$h.'&zc=1&q=100'.$a.'" title="'.$titulo.'" alt="'.$alt.'">
				      </a>
				';
			}
		}else{
			echo '
				  <img src="'.BASE.'/tim.php?src='.BASE.'/images/default.jpg&w='.$w.'&h='.$h.'&zc=1&q=100'.$a.'" title="'.$titulo.'" alt="'.$alt.'">
			';
		}
	}

/***************************************
GET CAT
****************************************/

	function getCat($catId, $campo = NULL){
		$catId = mysql_real_escape_string($catId);
		$readCategoria = read('up_cats', "WHERE id = '$catId'");
		if($readCategoria){
			if($campo){
				foreach($readCategoria as $cat){
					return $cat[$campo];
				}
			}else{
				return $readCategoria;
			}
		}else{
			return "Erro ao ler categoria";
		}
	}

/***************************************
GET AUTOR
****************************************/

	function getAutor($autorId, $campo = NULL){
		$autorId = mysql_real_escape_string($autorId);
		$readAutor = read('up_users',"WHERE id = '$autorId'");
		
		//@param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
		//$email = "someone@somewhere.com";
		//$default = "https://www.somewhere.com/homestar.jpg";
		//$size = 40;
		//$grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
		
		if($readAutor){
			foreach($readAutor as $autor);
			if(!$autor['avatar']):
				//$default = BASE.'/images/default.jpg'
				$gravatar = 'https://www.gravatar.com/avatar/';
				$gravatar .= md5(strtolower(trim($autor['email'])));
				$gravatar .= '?d=mm&s=180';
				$autor['foto'] = $gravatar;
			endif;
			if(!$campo){
				return $autor;
			}else{
				return $autor[$campo];
			}
		}else{
			return "Erro ao ler autor";
		}
	}
	
/***************************************
GET USER
****************************************/
	
	function getUser($user, $nivel = NULL){
		if($nivel != NULL){
			$readUser = read('up_users',"WHERE id = '$user'");
			if($readUser){
				foreach($readUser as $usuario);
				if($usuario['nivel'] <= $nivel && $usuario['nivel'] != '0' && $usuario['nivel'] <= 4){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return true;
		}
	}
	
?>