<?php 
	if(function_exists(getUser)){
		if(!getUser($_SESSION['autUser']['id'],'4')){
			header('Location: '.BASE.'/sessao/acesso-restrito');
		}
	}else{
		header('Location: '.BASE.'/sessao/acesso-restrito');
	}
	
	$userId = $_SESSION['autUser']['id'];
	$readUser = read('up_users',"WHERE id = '$userId'");
	if(!$readUser){
		header('Location: '.BASE.'/sessao/acesso-restrito');
	}else
		foreach($readUser as $user);
		if($user['status'] != '1') : header('Location: '.BASE.'/sessao/acesso-restrito'); endif;
?>
<title>Perfil de <?php echo $user['nome'] ?> em <?php echo SITENAME; ?></title>
<meta name="title" content="Perfil de <?php echo $user['nome'] ?> em <?php echo SITENAME; ?>" />
<meta name="description" content="<?php echo SITEDESC; ?>" />
<meta name="keywords" content="<?php echo SITETAGS; ?>" />
<meta name="author" content="<?php echo SITEAUTOR; ?>" />   
<meta name="url" content="<?php echo BASE.'/pagina/perfil'; ?>" />
<meta name="language" content="pt-br" /> 
<meta name="robots" content="NOINDEX,NOFOLLOW" /> 
</head>
<body>

<div id="site">

<?php setArq('tpl/header'); ?>

<div id="content">

<div class="form">
	<h1 class="pgtitulo">Bem vindo(a) <?php echo $user['nome']; ?> ao seu perfil.</h1>
    
    <div class="left" style="float:right;">
    	<h2>Confira e atualize seu avatar:</h2>
        <p>Sua cara em nosso site atualmente é:</p>
		<?php 
			$autor = getAutor($user['id']);
			$avatar = ($autor['avatar'] != '' ? BASE.'/tim.php?src='.BASE.'/uploads/avatars/'.$autor['avatar'].'&w=80&h=80&zc=1&q=100&a=t' : $autor['foto']);
		?>
        <img src="<?php echo $avatar;?>" width="80" height="80" title="<?php echo $autor['nome']; ?>" alt="Avatar de <?php echo $autor['nome']; ?>"/>
            
        <p>Deseja alterar este avatar?</p>
		<?php
			if(isset($_POST['sendAvatar'])){
				$newAvatar = $_FILES['img'];
				$tmp = $newAvatar['tmp_name']; 
				$ext = substr($newAvatar['name'],-3);
				$nome = md5(time()).'.'.$ext;
				$pasta = 'uploads/avatars/';
				if(file_exists($pasta.$user['avatar']) && !is_dir($pasta.$user['avatar'])){
					unlink($pasta.$user['avatar']);
				}
				uploadImage($tmp, $nome, '200', $pasta);
				$datas = array('avatar' => $nome);
				update('up_users', $datas, "id = '$user[id]'");
				header('Location: '.BASE.'/pagina/perfil');
			}
		?>
        <form name="avatar" action="" method="post" enctype="multipart/form-data">
        	<label style="width:320px;">
				<input type="file" name="img" atyle="width:290px" />
			</label>
			<input type="submit" name="sendAvatar" value="Enviar foto" class="btn" />
		</form>
        <br />
        <h2>Atualmente sua conta é:</h2>
		<?php 
			$nivel = ($user['nivel'] == 1 ? 'Admin' : ($user['nivel'] == 2 ? 'Editor' : ($user['nivel'] == 3 ? 'Premium' : 'Leitor (FREE)')));
		?>
		
        <p style="font:bold 20px Verdana, Geneva, sans-serif; color:#0CF; margin:0;"><?php echo $nivel; ?></p>
        
		<?php
		if($user['nivel'] == 4 ){
		?>
		<p>Que tal mudar para Premium agora mesmo, e ter acesso a todo o melhor conteúdo da internet?</p>
        
        <p><strong>É simples:</strong> Basta clicar no botão abaixo para ser direcionado ao PagSeguro, assim que identificarmos seu pagamento, mudaremos sua conta para Premium.</p>
		
		<!-- Declaração do formulário -->
		<form target="pagseguro" method="post" action="https://pagseguro.uol.com.br/v2/checkout/cart.html?action=add">
			<!-- Campos obrigatórios -->
			<input type="hidden" name="receiverEmail" value="<?php echo EMAILPAGSEG; ?>">
			<input type="hidden" name="currency" value="BRL">
			
			<!-- Itens de pagamento (ao menos m item é obrigatório) -->
			<input type="hidden" name="itemId" value="01">
			<input type="hidden" name="itemDescription" value="Conta Premium para <?php echo $user['nome']; ?>">
			<input type="hidden" name="itemAmount" value="9.90">
			<input type="hidden" name="itemQuantity" value="1">
			<input type="hidden" name="itemWeight" value="0">
			
			<!-- Código de referência do pagamento no seu sistema (opcional) -->
			<input type="hidden" name="reference" value="User ID: <?php echo $user['id'];?>">
			
			<!-- Submit do form (obrigatório) -->
			<input type="submit" value="Pagar" class="btn" style="padding:10px 30px;">
			<!-- <input type="image" name="submit" src="https://p.simg.uol.com.br/out/pagseguro/i/botoes/pagamentos/120x53-pagar.gif" alt="Pague com PagSeguro"> -->
		</form>
		
		<?php
		}elseif($user['nivel'] == '3'){
			$diaHoje = strtotime(date('Y-m-d'));
			$diaFim  = strtotime(date('Y-m-d',strtotime($user['premium_end'])));
			$faltaDias = $diaFim - $diaHoje;
			$timeDeUmDia = 24*60*60;
			$termina = $faltaDias/$timeDeUmDia;
		?>
			<p>Sua conta Premium acaba em <strong><?php echo round($termina); ?></strong> dia(s), após este período você podera renvar sua assinatura novamente!</p>
		<?php 
		}
		?>
	</div><!-- //left -->
    
    <div class="right" style="float:left;">
		
		<?php 
			if(isset($_POST['sendForm'])){
				$c['senha'] = mysql_real_escape_string($_POST['senha']);
				$c['resenha'] = mysql_real_escape_string($_POST['resenha']);
				if(in_array('',$c)){
					echo '<p class="erro radius">Erro: Informe a senha e a repita!</p>';
				}elseif($c['senha'] != $c['resenha']){
					echo '<p class="erro radius">Erro: As senhas digitadas não conferem!</p>';
				}elseif(strlen($c['senha']) < 8 || strlen($c['senha']) > 12){
					echo '<p class="erro radius">Erro: A senha deve ter entre 8 e 12 caracteres!</p>';
				}else{
					$_SESSION['autUser']['senha'] = md5($c['senha']);
					$datas = array(
						'senha' => md5($c['senha']),
						'code'  => $c['senha']
					);
					update('up_users', $datas, "id = '$user[id]'");
					header('Location: '.BASE.'/pagina/perfil');
					$_SESSION['return'] = '<p class="accept radius">Sucesso: Sua senha foi atualizada!</p>';
				}
			}elseif(!empty($_SESSION['return'])){
				echo $_SESSION['return'];
				unset($_SESSION['return']);
			}
		?>
    	<form name="cadastro" action="" method="post">
        	<fieldset>
            	<legend>Identificação:</legend>
            	<label>
                	<span class="tt">Nome completo:</span>
                    <span class="campos"><?php echo $user['nome'];?></span>
                </label>
                <label>
                	<span class="tt">CPF:</span>
                     <span class="campos">XXX.XXX.XXX-<?php echo substr($user['cpf'],-2);?></span>
                </label>
                <label>
                	<span class="tt">E-mail:</span>
                    <span class="campos"><?php echo $user['email'];?></span>
                </label>
            </fieldset>
            
            <fieldset>
            	<legend>Endereço:</legend>
            	<label>
                     <span class="campos"><?php echo $user['rua'];?>, <?php echo $user['cidade'];?> - CEP: <?php echo $user['cep'];?></span>
                </label>
            </fieldset>
            
            <fieldset>
            	<legend>Contato:</legend>
            	<label>
                	<span class="tt">Telefone / Celular:</span>
                    <span class="campos"><?php echo $user['telefone'];?> / <?php echo $user['celular'];?></span>
                </label>
            </fieldset>
            <fieldset>
            	<legend>Alterar Senha:</legend>
            	<label>
                	<span class="tt">Nova senha:</span>
                    <input type="password" name="senha">
                </label>
                <label>
                	<span class="tt">Repita sua nova senha:</span>
                    <input type="password" name="resenha">
                </label>
            </fieldset>
            <input type="submit" value="Atualizar senha" name="sendForm" class="btn">
        </form>
		
    </div><!-- //right -->
    
</div><!-- /form -->
</div><!-- //content -->