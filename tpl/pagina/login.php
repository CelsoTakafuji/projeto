<?php if(!empty($_SESSION['autUser'])){ header('Location: '.BASE.'/pagina/perfil'); } ?>

<title>Login | <?php echo SITENAME; ?></title>
<meta name="title" content="Login | <?php echo SITENAME.' - '.SITETAGS; ?>" />
<meta name="description" content="<?php echo SITEDESC; ?>" />
<meta name="keywords" content="<?php echo SITETAGS; ?>" />
<meta name="author" content="<?php echo SITEAUTOR; ?>" />   
<meta name="url" content="<?php echo BASE; ?>/pagina/login" />  
<meta name="language" content="pt-br" /> 
<meta name="robots" content="INDEX,FOLLOW" />
</head>
<body>
	
    <!--
    <div id="modal" class="modal">
    	<div class="label">
                <a href="#" class="close" title="fechar" onClick="document.getElementById('modal').style.display='none';">
                    <img title="fechar" src="<?php //setHome();?>/tpl/images/ac_del.png">
                </a>
                <p>
                	<strong>Mensagem a ser exibida:</strong>
                    Seus dados foram enviados para seu e-email. Confira e acesse o link no corpo da mensagem recebida!
                </p>
        </div><!-- //label -->
    <!--</div><!-- //modal -->
    

<div id="site">
<?php setArq('tpl/header'); ?>

<div id="content">

<?php 
	if(empty($url['2'])){ 
?>

<div class="form">
	<h1 class="pgtitulo">Minha conta:</h1>
	
    <div class="left">
    	<h2>Informe seus dados ao lado para acessar sua conta Leitor ou Premium.</h2>
        <p>Lembre-se de manter seus dados em segurança. Sua conta é de uso próprio e intranferivel, você pode alterar seus dados e migrar sua
        conta quando quiser. Basta acessar o sistema e interagir com o mesmo.</p>
        <p>Caso tenha esquecido seus dados basta <a class="link" href="<?php setHome();?>/pagina/login/recover" title="Recuperar senha">clicar aqui</a>
         para recuperar sua senha!</p>
    </div><!-- //left -->
    
    <div class="right">
		<?php 
			if(isset($_POST['sendLogar'])){
				$f['email'] = mysql_real_escape_string($_POST['email']);
				$f['senha'] = mysql_real_escape_string($_POST['senha']);
				if(in_array('',$f)){
					echo '<p class="erro radius">Erro: Você deve informar seu login e senha!</p>';
				}elseif(!valMail($f['email'])){
					echo '<p class="erro radius">Erro: E-mail informado não tem um formato válido!</p>';
				}elseif(strlen($f['senha']) < 8 || strlen($f['senha']) > 12){
					echo '<p class="erro radius">Erro: Senha digitada não é válida!</p>';
				}else{
					$readUser = read('up_users',"WHERE email = '$f[email]'");
					if($readUser){
						foreach($readUser as $user);
						if($user['email'] != $f['email'] || $user['senha'] != md5($f['senha'])){
							echo '<p class="erro radius">Erro: Senha não confere com o e-mail digitado!</p>';
						}elseif($user['status'] != '1'){
							echo '<p class="erro radius">Erro: Sua conta ainda não está ativa. Favor verifique a ativação em seu e-mail!</p>';
						}else{
							$_SESSION['autUser'] = $user;
							header('Location: '.BASE.'/pagina/perfil');
						}
					}else{
						echo '<p class="erro radius">Erro: E-mail digitado não confere!</p>';
					}
				}
			} 
		?>	
	
    	<form name="contato" action="" method="post">
        	<fieldset>
            	<legend>Acessar:</legend>
            	<label>
                	<span class="tt">E-mail:</span>
                    <input type="text" name="email" value="<?php if($f['email']) echo $f['email']; ?>">
                </label>
                <label>
                	<span class="tt">Senha:</span>
                    <input type="password" name="senha" value="<?php if($f['senha']) echo $f['senha']; ?>">
                </label>
            </fieldset>
            <input type="submit" value="Entrar agora" name="sendLogar" class="btn">
        </form>
    </div><!-- //right -->
    
</div><!-- /form -->
<?php 
	} elseif($url[2] == 'recover') {
?>
<div class="form">
	<h1 class="pgtitulo">Recuperar senha:</h1>
    
    <div class="left">
    	<h2>Veja como é fácil recuperar sua senha:</h2>
        <p>Informe ao lado seu e-mail de acesso a nosso sistema e seu CPF e clique em recuperar dados.</p>
        <p>Se os dados forem corretos enviaremos um e-mail no endereço informado!</p>
        <p>Basta acessar seu e-mail e acessar o link contido no corpo da mensagem para redefinir sua senha.</p>
    </div><!-- //left -->
    
    <div class="right">
		<?php
			if(isset($_POST['sendRecover'])){
				$r['email'] = mysql_real_escape_string($_POST['email']);
				$r['cpf'] 	= mysql_real_escape_string($_POST['cpf']);
				if(in_array('',$r)){
					echo '<p class="erro radius">Erro: Favor informe seu e-mail e cpf!</p>';
				}elseif(!valMail($r['email'])){
					echo '<p class="erro radius">Erro: E-mail informado não tem um formato válido!</p>';
				}elseif(!valCpf($r['cpf'])){
					echo '<p class="erro radius">Erro: CPF informado não é válido!</p>';
				}else{
					$readRecover = read('up_users',"WHERE email = '$r[email]'");
					if($readRecover){
						foreach($readRecover as $recover);
						if($recover['email'] != $r['email'] || $recover['cpf'] != $r['cpf']){
							echo '<p class="erro radius">Erro: CPF informado não confere com o e-mail!</p>';
						}elseif($recover['status'] != '1'){
							echo '<p class="erro radius">Erro: Sua conta ainda não está ativa. Favor verifique a ativação em seu e-mail!</p>';
						}else{
							$linkAtv = BASE.'/pagina/login/change/'.base64_encode($recover['email']).'and'.$recover['senha'];
							$msgSend  = '<p style="font:18px \'Trebuchet MS\', Arial, Helvetica, sans-serif; color:#09F;">Prezado(a) '.$recover['nome'].', recebemos sua solicitação de recuperação de dados!</p>'
										.'<p style="font:12px Arial, Helvetica, sans-serif; color:#333;">Estamos entrando em contato, pois recebemos uma solicitação do seu e-mail para recuperar seus dados de acesso em nosso site '.SITENAME.'. Caso este e-mail não tenha sido enviado por você, favor ignore esta mensagem!</p>'
										.'<hr /><p style="font:14px bold \'Trebuchet MS\', Arial, Helvetica, sans-serif; color:#069;">Para recuperar sua senha:</p>'
										.'<p style="font:12px Arial, Helvetica, sans-serif; color:#333;">Basta que você clique em "<a href="'.$linkAtv.'" title="Recuperar agora">Recuperar senha</a>" para acessar nosso site e atualizar a sua senha. Caso seu navegador não siga o link. Copie a url abaixo e cole no mesmo!</p>'
										.'<p style="font:10px Arial, Helvetica, sans-serif; color:#069;">'.$linkAtv.'</p><hr />'
										.'<p style="font:bold 16px Tahoma, Geneva, sans-serif; color:#900;">Atenciosamente '.SITENAME.' enviada em: '.date('d/m/Y H:i').'</p>';
							sendMail('Recupere seus dados!', $msgSend, MAILUSER, SITENAME, $recover['email'], $recover['nome']);
							echo '<p class="accept radius">Sucesso: Seus dados conferem enviamos um e-mail para que você possa alterar sua senha. Verifique e siga o link contido no mesmo!</p>';
						}
					}else{
						echo '<p class="erro radius">Erro: E-mail informado não confere com nossos usuários!</p>';
					}
				}
			}
		?>
	
        <form name="contato" action="" method="post">
        	<fieldset>
            	<legend>Recuperar:</legend>
            	<label>
                	<span class="tt">E-mail:</span>
                    <input type="text" name="email" value="<?php if($r['email']) echo $r['email']; ?>">
                </label>
                <label>
                	<span class="tt">CPF:</span>
                    <input type="text" name="cpf" class="formCpf" value="<?php if($r['cpf']) echo $r['cpf']; ?>">
                </label>
            </fieldset>
            <input type="submit" value="Receber dados" name="sendRecover" class="btn">
        </form>
    </div><!-- //right -->
    
</div><!-- /form -->

<?php 
	} elseif($url[2] == 'change') {
?>

<div class="form">
	<h1 class="pgtitulo">Alterar senha:</h1>
	    
    <div class="left">
    	<h2>Olá, você deve informar uma nova senha:</h2>
        <p>Está página está sendo exibida porque você solicitou uma recuperação de dados.</p>
        <p>Para concluir essa alteração você deve informar sua nova senha ao lado!</p>
        <p>Lemre-se. A senha deve ter de 8 a 12 caracteres.</p>
    </div><!-- //left -->
    
    <div class="right">
		<?php
			if(isset($_POST['sendChange'])){
				$s['senha']   = mysql_real_escape_string($_POST['senha']);
				$s['resenha'] = mysql_real_escape_string($_POST['resenha']);
				$userId = $_POST['userId'];
				if(in_array('',$s)){
					echo '<p class="erro radius">Erro: Favor informe e repita sua nova senha!</p>';
				}elseif($s['senha'] != $s['resenha']){
					echo '<p class="erro radius">Erro: As senhas digitadas não conferem!</p>';
				}elseif(strlen($s['senha']) < 8 || strlen($s['senha']) > 12){
					echo '<p class="erro radius">Erro: As senhas devem ter de 8 à 12 caracteres!</p>';
				}else{
					$datas = array('senha' => md5($s['senha']), 'code' => $s['senha']);
					update('up_users', $datas, "id = '$userId'");
					echo '<p class="accept radius">Sucesso: Sua senha foi alterada. Estamos redirecionando você ao login!</p>';
					header('Refresh: 5;url='.BASE.'/pagina/login');
					$validSenha = true;
				}
			}
			
			if(!$validSenha){
				$recoverCode = mysql_real_escape_string($url[3]);
				$recoverCode = explode('and',$recoverCode);
				$email = base64_decode($recoverCode[0]);
				$senha = $recoverCode[1];
				$readUserChange = read('up_users',"WHERE email = '$email'");
				if(!$readUserChange){
					echo '<p class="erro radius">Erro: Não existem usuários de acordo com a url informada!</p>';
				}else{
					foreach($readUserChange as $userChange);
					if($userChange['email'] != $email || $userChange['senha'] != $senha){
						echo '<p class="erro radius">Erro: Dados não conferem, não será possível alterar sua senha!</p>'; 
					}else{
		?>
				<form name="contato" action="" method="post">
					<fieldset>
						<legend>Alterar senha:</legend>
						<label>
							<span class="tt">Nova senha:</span>
							<input type="password" name="senha" value="<?php if($s['senha']) echo $s['senha']; ?>">
						</label>
						<label>
							<span class="tt">Repita a nova senha:</span>
							<input type="password" name="resenha" value="<?php if($s['resenha']) echo $s['resenha']; ?>">
						</label>
					</fieldset>
					<input type="hidden" name="userId" value="<?php echo $userChange['id']; ?>">
					<input type="submit" value="Alterar minha senha" name="sendChange" class="btn">
				</form>
		<?php
					}
				}
			}
		?>
	
    </div><!-- //right -->
    
</div><!-- /form -->
<?php 
	}
?>
</div><!-- //content -->