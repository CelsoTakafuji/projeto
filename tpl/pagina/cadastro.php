<?php
	if(!empty($url[2]) && $url[2] == 'ativar-conta'){
		$codeatv = mysql_real_escape_string($url[3]);
		$codeatv = explode('and',$codeatv);
		$emailAtv = base64_decode($codeatv[0]);
		$senhaAtv = $codeatv[1];
		$readAtivar = read('up_users',"WHERE email = '$emailAtv' AND senha = '$senhaAtv'");
		if($readAtivar){
			foreach($readAtivar as $atv);
			if($atv['status'] == 1){
				header('Location: '.BASE.'/sessao/erro-ao-ativar');
			}else{
				$datas = array('status' => '1');
				update('up_users',$datas,"id = '$atv[id]'");
				header('Location: '.BASE.'/sessao/ativada-com-sucesso');
			}
		}else{
			header('Location: '.BASE.'/sessao/erro-ao-ativar');
		}
	}
?>
<title>Cadastro | <?php echo SITENAME; ?></title>
<meta name="title" content="Cadastro | <?php echo SITENAME.' - '.SITETAGS; ?>" />
<meta name="description" content="Cadastro | <?php echo SITEDESC; ?>" />
<meta name="keywords" content="Cadastro | <?php echo SITETAGS; ?>" />
<meta name="author" content="<?php echo SITEAUTOR; ?>" />  
<meta name="url" content="<?php echo BASE; ?>/pagina/cadastro" />  
<meta name="language" content="pt-br" /> 
<meta name="robots" content="INDEX,FOLLOW" /> 
</head>
<body>

<div id="site">

<?php setArq('tpl/header'); ?>

<div id="content">

<div class="form">
	<h1 class="pgtitulo">Participe:</h1>
    
    <div class="left">
    	<h2>Cadastre-se e participe de nossa rede de leitores.</h2>
        <p>Sendo um leitor cadastrado você tem acesso liberado a maioria dos nossos artigos e novidades.</p>
        <p>Alem de participar da rede e poder interagir em todos os artigos, expressando sua opnião e interagindo com os outros leitores!</p>
        <p>Existe ainda a conta premium. Onde você tem acesso a 100% do conteúdo do site, Colabora com a expansão do site e 
        tem coteúdo especial liberado para você!</p>
        <p><strong>Cadastre-se agora mesmo!</strong></p>
        <p>Tenha acesso ao melhor conteúdo informativo da internet!</p>
        <img src="<?php setHome();?>/tpl/images/premium.png" title="Conta Premium" alt="Conta Premium">
    </div><!-- //left -->
    
    <div class="right">
	
	<?php
		if(isset($_POST['sendForm'])){
			//Identificação
			$f['nome'] 		= mysql_real_escape_string($_POST['nome']);
			$f['cpf'] 		= mysql_real_escape_string($_POST['cpf']);
			$f['email'] 	= mysql_real_escape_string($_POST['email']);
			$f['code'] 		= mysql_real_escape_string($_POST['senha']);
			
			//Endereço
			$f['rua'] 		= mysql_real_escape_string($_POST['rua']);
			$f['cidade'] 	= mysql_real_escape_string($_POST['cidade']);
			$f['cep'] 		= mysql_real_escape_string($_POST['cep']);
			
			//Contato
			$f['telefone'] 	= mysql_real_escape_string($_POST['telefone']);
			$f['celular'] 	= mysql_real_escape_string($_POST['celular']);
			
			if(in_array('',$f)){
				echo '<p class="erro radius">Erro: Todos os campos devem ser preenchidos para que possamos efetuar seu cadastro!</p>';
			}elseif(!valCpf($f['cpf'])){
				echo '<p class="erro radius">Erro: O CPF informado não é válido, favor informe seu CPF. Obrigado!</p>';
			}elseif(!valMail($f['email'])){
				echo '<p class="erro radius">Erro: O e-mail informado não tem formato válido, favor informe seu E-MAIL!</p>';
			}elseif(strlen($f['code']) < 8 || strlen($f['code']) > 12){
				echo '<p class="erro radius">Erro: Campo senha deve ter de 8 à 12 caracteres!</p>';
			}else{
				$verifica = read('up_users',"WHERE email = '$f[email]' OR cpf = '$f[cpf]'");
				if($verifica){
					echo '<p class="erro radius">Erro: E-mail ou CPF informados já estão cadastrados em nosso sistema!</p>';
				}else{
					$f['senha']  	= md5($f['code']);
					$f['nivel']  	= '4';
					$f['status'] 	= '0';
					$f['cad_data'] 	= date('Y-m-d H:i:s');
					$linkAtv = BASE.'/pagina/cadastro/ativar-conta/'.base64_encode($f['email']).'and'.$f['senha'];
					$msgSend  = '<p style="font:18px \'Trebuchet MS\', Arial, Helvetica, sans-serif; color:#09F;">Prezado(a) '.$f['nome'].', recebemos sua solicitação de cadastro!</p>'
								.'<p style="font:12px Arial, Helvetica, sans-serif; color:#333;">Estamos entrando em contato, pois recebemos uma solicitação do seu e-mail para cadastro em nosso site '.SITENAME.'. Caso este e-mail não tenha sido enviado por você, favor ignore esta mensagem!</p>'
								.'<hr /><p style="font:14px bold \'Trebuchet MS\', Arial, Helvetica, sans-serif; color:#069;">Para ativar sua conta:</p>'
								.'<p style="font:12px Arial, Helvetica, sans-serif; color:#333;">Basta que você clique em "<a href="'.$linkAtv.'" title="Ativar agora">Ative sua conta</a>" para que o processo seja executado automaticamente. Caso seu navegador não siga o link. Copie a url abaixo e cole no mesmo!</p>'
								.'<p style="font:10px Arial, Helvetica, sans-serif; color:#069;">'.$linkAtv.'</p><hr />'
								.'<p style="font:bold 16px Tahoma, Geneva, sans-serif; color:#900;">Atenciosamente '.SITENAME.'</p>';
					sendMail('Ative sua conta!', $msgSend, MAILUSER, SITENAME, $f['email'], $f['nome']);
					create('up_users',$f);
					header('Location: '.BASE.'/sessao/ative-sua-conta');
				}
			}
		}
		?>
		
    	<form name="cadastro" action="" method="post">
        	<fieldset>
            	<legend>Identificação:</legend>
            	<label>
                	<span class="tt">Nome completo:</span>
                    <input type="text" name="nome" value="<?php if($f['nome']) echo $f['nome']; ?>">
                </label>
                <label>
                	<span class="tt">CPF:</span>
                    <input type="text" name="cpf" class="formCpf" value="<?php if($f['cpf']) echo $f['cpf']; ?>">
                </label>
                <label>
                	<span class="tt">E-mail:</span>
                    <input type="text" name="email" value="<?php if($f['email']) echo $f['email']; ?>">
                </label>
                <label>
                	<span class="tt">Senha:</span>
                    <input type="password" name="senha" value="<?php if($f['code']) echo $f['code']; ?>">
                </label>
            </fieldset>
            
            <fieldset>
            	<legend>Endereço:</legend>
            	<label>
                	<span class="tt">Rua + Número + Bairro:</span>
                    <input type="text" name="rua" value="<?php if($f['rua']) echo $f['rua']; ?>">
                </label>
                <label>
                	<span class="tt">Cidade + UF:</span>
                    <input type="text" name="cidade" value="<?php if($f['cidade']) echo $f['cidade']; ?>">
                </label>
                <label>
                	<span class="tt">CEP:</span>
                    <input type="text" name="cep" class="formCep" value="<?php if($f['cep']) echo $f['cep']; ?>">
                </label>
            </fieldset>
            
            <fieldset>
            	<legend>Contato:</legend>
            	<label>
                	<span class="tt">Telefone:</span>
                    <input type="text" name="telefone" class="formFone" value="<?php if($f['telefone']) echo $f['telefone']; ?>">
                </label>
                <label>
                	<span class="tt">Celular:</span>
                    <input type="text" name="celular" class="formCel" value="<?php if($f['celular']) echo $f['celular']; ?>">
                </label>
            </fieldset>
            <input type="reset" value="Limpar Campos" class="reset">
            <input type="submit" value="Cadastrar meus dados" name="sendForm" class="btn">
        </form>
    </div><!-- //right -->
    
</div><!-- /form -->
</div><!-- //content -->