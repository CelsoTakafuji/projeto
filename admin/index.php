<?php
	ob_start();
	session_start();
	require('../dts/dbaSis.php');
	require('../dts/outSis.php');
	
	if(!empty($_SESSION['autUser'])){
		header('Location: index2.php');
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
<meta name="url" content="<?php echo BASE.'/admin/index'; ?>" />
   
<meta name="language" content="pt-br" /> 
<meta name="robots" content="NOINDEX,NOFOLLOW" /> 

<link rel="icon" type="image/png" href="ico/chave.png" />
<link rel="stylesheet" type="text/css" href="css/login.css" />
<link rel="stylesheet" type="text/css" href="css/geral.css" />

</head>

<body>

<div id="login">

	<img src="images/login-logo.png" alt="<?php echo SITENAME; ?> - Área administrativa | Login" title="<?php echo SITENAME; ?> - Área administrativa | Login" />
	
	<?php 
		if(isset($_POST['sendLogin'])){
			$f['email'] = mysql_real_escape_string($_POST['email']);
			$f['senha'] = mysql_real_escape_string($_POST['senha']);
			$f['salva'] = mysql_real_escape_string($_POST['remember']);
			
			if(!$f['email'] || !valMail($f['email'])){
				echo '<span class = "ms al">Campo e-mail está vazio ou não tem um formato válido!</span>';
			}elseif(strlen($f['senha']) < 7 || strlen($f['senha']) > 12){
				echo '<span class = "ms al">Senha deve ter entre 7 e 12 caracteres!</span>';
			}else{
				$autEmail = $f['email'];
				$autSenha = md5($f['senha']);
				$readAutUser = read('up_users', "WHERE email = '$autEmail'");
				if($readAutUser){
					foreach($readAutUser as $autUser);
					if($autEmail == $autUser['email'] && $autSenha == $autUser['senha']){
						if($autUser['nivel'] == 1 || $autUser['nivel'] == 2){
							if($f['salva']){
								$cookiesalva = base64_encode($autEmail).'&'.base64_encode($f['senha']);
								setcookie('autUser',$cookiesalva,time()+60*60*24*30,'/');
							}else{
								setcookie('autUser','',time()+3600,'/');
							}
							
							$_SESSION['autUser'] = $autUser;
							header('Location: '.$_SERVER['PHP_SELF']);
							
						}else{
							echo '<span class = "ms al">Seu nível não permite acesso a está área. Vamos redirecionar você para o login de usuários.</span>';
							header('Refresh: 5;url='.BASE.'/pagina/login');
						}
					}else{
						echo '<span class = "ms al">Senha informada não confere!</span>';
					}
					
					//INICIAR A SESSÃO
					
				}else{
					echo '<span class = "ms al">E-mail informado não é válido!</span>';
				}
			}
		}elseif(!empty($_COOKIE['autUser'])){
			$cookie = $_COOKIE['autUser'];
			$cookie = explode('&',$cookie);
			$f['email'] = base64_decode($cookie[0]);
			$f['senha'] = base64_decode($cookie[1]);
			$f['salva'] = 1;
		}
			if(!$_GET['remember']){
		?>
    	<form name="login" action="" method="post">
        	<label>
            	<span>E-mail:</span>
                <input type="text" class="radius" name="email" values="<?php if($f['email']) echo $f['email']; ?>"/>
            </label>
            <label>
            	<span>Senha:</span>
                <input type="password" class="radius" name="senha" values="<?php if($f['senha']) echo $f['senha']; ?>"/>
            </label>
            <input type="submit" value="Logar-se" name="sendLogin" class="btn" />
            
            <div class="remember">
            	<input type="checkbox" name="remember" value="1" <?php if($f['salva']) echo 'checked=/"checked/"'; ?> />
				Lembrar meus dados de acesso!
            </div>
            <a href="index.php?remember=true" class="link" title="Esqueci minha senha!">Esqueci minha senha!</a>
        </form>
		<?php
			}else{
				if(isset($_POST['sendRecover'])){
					$recover = mysql_real_escape_string($_POST['email']);
					$readRec = read('up_users',"WHERE email = '$recover'");
					if(!$readRec){
						echo('<span class = "ms no">Erro: E-mail não confere!</span>');
					}else{
						foreach($readRec as $rec);
						if($rec['nivel'] == 1 || $rec['nivel'] == 2){
							$msg = '<h3 style="font:16px \'Trebuchet MS\', Arial, Helvetica, sans-serif; color:#099;">Prezado '.$rec['nome'].', recupere seu acesso!</h3><p style="font:bold 12px Arial, Helvetica, sans-serif; color:#666;">Estamos entrando em contato, pois foi solicitado em nosso nível administrativo / editor a recuperação de dados de acesso. Verifique logo abaixo os dados de seu usuário:</p><hr>
							<p style="font:italic 14px \'Trebuchet MS\', Arial, Helvetica, sans-serif; color:#069;">E-mail: '.$rec['email'].'<br>Senha: '.$rec['code'].'</p><hr><p style="font:bold 12px Arial, Helvetica, sans-serif; color:#F00;">Recomendamos que você altere seus dados em seu perfil após efetuar o login!</p><hr>
							<p style="font:italic 12px Arial, Helvetica, sans-serif; color:#666;">Atenciosamente a administração - '.date('d/m/Y H:i:s').' - <a style="color:#900" href="#" title="Napo Napo Napo">NAPO</a><hr><img alt="Napo Napo Napo" title="Napo Napo Napo" src="#"></p>';
							sendMail('Recupere seus dados!', $msg, MAILUSER, SITENAME, $rec['email'], $rec['nome']);
							echo '<span class = "ms ok">Seus dados foram enviados com sucesso para: <strong>'.$rec['email'].'</strong>. Favor verifique!</span>';
						}else{
							echo '<span class = "ms al">Seu nível não permite acesso a está área. Vamos redirecionar você para o login de usuários.</span>';
							header('Refresh: 5;url='.BASE.'/pagina/login');
						}
					}
				}
		?>
		        
        <form name="recover" action="" method="post">
        	<span class="ms in">Informe seu e-mail para que possamos enviar seus dados de acesso!</span>
        	<label>
            	<span>E-mail:</span>
                <input type="text" class="radius" name="email" value="<?php if($recover) echo $recover; ?>"/>
            </label>
            <input type="submit" value="Recuperar dados" name="sendRecover" class="btn" />
            <a href="index.php" class="link" title="Voltar">Voltar</a>
        </form>
    	<?php
			}		
		?>
</div><!-- //login -->

</body>
<?php
	ob_end_flush();
?>
</html>