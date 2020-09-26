<title>Fale Conosco | <?php echo SITENAME; ?></title>
<meta name="title" content="Fale Conosco | <?php echo SITENAME.' - '.SITETAGS; ?>" />
<meta name="description" content="Entre em contato com a equipe de suporte" />
<meta name="keywords" content="Contato, Fale conosco, Entre em contato, Suporte" />
<meta name="author" content="<?php echo SITEAUTOR; ?>" />  
<meta name="url" content="<?php echo BASE; ?>/pagina/contato" />  
<meta name="language" content="pt-br" /> 
<meta name="robots" content="INDEX,FOLLOW" /> 
</head>
<body>

<div id="site">

<?php setArq('tpl/header'); ?>

<div id="content">

<div class="form">
	<h1 class="pgtitulo">Fale conosco:</h1>
    
    <div class="left">
    	<h2>Dúvidas, críticas ou sugestões?</h2>
        <p>Nosso setor de atendimento está sempre pronto para ouvir você. Contudo pedimos que envie sua mensagem e aguarde!</p>
        <p>Temos uma grande quantidade de mensagens por dia. Mas damos a você a garantia do atendimento mais ágil possível. Nossas respostas
        são dadas em no máximo 48horas úteis.</p>
        <p>Deixe sua dúvida, critica ou sugestão aqui. Nossa equipe vai dar o melhor para lhe reponder com a melhor solução.</p>
        <p><strong>Você ainda pode entrar em contato por outros canais:</strong></p>
        <p><strong>Telefone:</strong> <?php echo TELEFONE; ?></p>
        <p><strong>Endereço:</strong> <?php echo ENDERECO; ?></p></p>
    </div><!-- //left -->
	
    <div class="right">
	
		<?php 
			if(isset($_POST['sendForm'])){
				$f['nome'] 		= mysql_real_escape_string($_POST['nome']);
				$f['assunto'] 	= mysql_real_escape_string($_POST['assunto']);
				$f['email'] 	= mysql_real_escape_string($_POST['email']);
				$f['mensagem'] 	= strip_tags(trim($_POST['mensagem']));
			
				if(in_array('',$f)){
					echo '<p class="erro radius">Erro: Desculpe, para que possamos enviar sua mensagem você deve preencher todos os campos. Obrigado!</p>';
				}elseif(!valMail($f['email'])){
					echo '<p class="erro radius">Erro: O e-mail que você informou não tem um formato válido, favor informe um e-mail válido. Obrigado!</p>';
				}else{
					$msgSend = '<p style="font:14px \'Trebuchet MS\', Arial, Helvetica, sans-serif; color:#333;">'.nl2br($f['mensagem']).'</p><hr /><h4>Nova mensagem via contato do site:</h4><p><strong>Nome: </strong>'.$f['nome'].'<br /><strong>E-mail: </strong>'.$f['email'].'<br /><strong>Assunto: </strong>'.$f['assunto'].'<br /><strong>Data: </strong>'.date('d/m/Y H:i');
					sendMail($f['assunto'], $msgSend, MAILUSER, SITENAME, MAILUSER, SITENAME, $f['email'], $f['nome']);
					$_SESSION['return'] = '<p class="accept radius">Sucesso: Obrigado por entrar em contato com o '.SITENAME.'. Recebemos a sua mensagem e estaremos respondendo em breve</p>';
					header('Location: '.BASE.'/pagina/contato');
				}
			}elseif(!empty($_SESSION['return'])){
				echo $_SESSION['return'];
				unset($_SESSION['return']);
			}
		?>
		
    	<form name="contato" action="" method="post">
        	<fieldset>
            	<legend>Entre em contato:</legend>
            	<label>
                	<span class="tt">Nome:</span>
                    <input type="text" name="nome" value="<?php if($f['nome']) echo $f['nome']; ?>">
                </label>
                <label>
                	<span class="tt">E-mail:</span>
                    <input type="text" name="email" value="<?php if($f['email']) echo $f['email']; ?>">
                </label>
                <label>
                	<span class="tt">Assunto:</span>
                    <input type="text" name="assunto" value="<?php if($f['assunto']) echo $f['assunto']; ?>">
                </label>
                <label>
                	<span class="tt">Mensagem:</span>
                    <textarea name="mensagem" rows="8"><?php if($f['mensagem']) echo $f['mensagem']; ?></textarea>
                </label>
            </fieldset>
            <input type="reset" value="Limpar Campos" class="reset">
            <input type="submit" value="Enviar Contato" name="sendForm" class="btn">
        </form>
    </div><!-- //right -->
    
</div><!-- /form -->
</div><!-- //content -->