<?php
ob_start(); session_start();
require('../dts/dbaSis.php');
require('../dts/getSis.php');
require('../dts/setSis.php');
require('../dts/outSis.php');

if(!$_SESSION['autUser']){
	header('Location: index.php');
}else{
	$userId      = $_SESSION['autUser']['id'];
	$readAutUser = read('up_users',"WHERE id = $userId");
	if($readAutUser){
		foreach($readAutUser as $autUser);
		if($autUser['nivel'] < 1 || $autUser['nivel'] > 2){
			header('Location: index.php');
		}
	}else{
		header('Location: '.BASE.'pagina/perfil');
	}
}

$readPremium = read('up_users', "WHERE nivel = '3' AND premium_end < NOW()");
if($readPremium){
	foreach($readPremium as $endPremium):
		$end = array('nivel' => '4', 'premium_end' => '0000-00-00 00:00:00');
		update('up_users', $end, "id = '$endPremium[id]'");
	endforeach;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Painel Administrativo - <?php echo SITENAME; ?></title>

<meta name="title" content="Painel Administrativo - <?php echo SITENAME; ?>" />
<meta name="description" content="Área restrita aos administradores do site: <?php echo SITENAME; ?>" />
<meta name="keywords" content="Login, Recuperar Senha" />

<meta name="author" content="<?php echo SITEAUTOR; ?>" />   
<meta name="url" content="<?php echo BASE.'/admin/index2'; ?>" />
   
<meta name="language" content="pt-br" /> 
<meta name="robots" content="NOINDEX,NOFOLLOW" /> 

<link rel="icon" type="image/png" href="ico/chave.png" />
<link rel="stylesheet" type="text/css" href="css/painel.css" />
<link rel="stylesheet" type="text/css" href="css/geral.css" />

</head>
<body>

<div id="painel">

	<?php require_once('includes/header.php'); ?>
    
    <div id="content">
		<?php require_once('includes/menu.php'); ?>
    	<div class="pg">
			<?php
				if(empty($_GET['exe'])){
					require('home.php');
				}elseif(file_exists($_GET['exe'].'.php')){
					require($_GET['exe'].'.php');
				}else{
					echo '<div class="bloco"><span class="ms in">Desculpe, a página que você está tentando acessar é inválida!</span></div>';
				}
			?>
        </div><!-- pg -->
    </div><!-- /content -->
    
<div style="clear:both"></div> 
<div id="footer">
	Desenvolvido por <a href="mailto:<?php echo EMAIL; ?>" title="Sistema desenvolvido por "><?php echo SITEAUTOR; ?></a>
</div><!-- //footer -->
</div><!-- //painel -->
</body>
	<?php 
		require_once('../js/jscSis.php');
		require_once('js/jsc.php'); 
		ob_end_flush(); 
	?>
</html>