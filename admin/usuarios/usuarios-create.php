<?php
if(function_exists(getUser)){
	if(!getUser($_SESSION['autUser']['id'],'1')){
		echo '<span class="ms al">Desculpe, você não tem permissão para gerenciar os usuários!</span>';
	}else{
?>
<div class="bloco form" style="display:block">
	<div class="titulo">
		Cadastrar Usuário:
		<a href="index2.php?exe=usuarios/usuarios" style="float:right" class="btnalt" title="Voltar">Listar Usuários</a>
	</div>
	
	<?php
		if(isset($_POST['sendForm'])){
			$f['nome'] 		= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$f['cpf'] 		= strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
			$f['email'] 	= strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$f['code'] 		= strip_tags(trim(mysql_real_escape_string($_POST['senha'])));
			$f['senha'] 	= md5($f['code']);
			$f['rua'] 		= strip_tags(trim(mysql_real_escape_string($_POST['rua'])));
			$f['cidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
			$f['cep'] 		= strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$f['telefone'] 	= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
			$f['celular'] 	= strip_tags(trim(mysql_real_escape_string($_POST['celular'])));
			$f['nivel'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nivel'])));
			$f['statusS'] 	= strip_tags(trim(mysql_real_escape_string($_POST['status'])));
			$f['status'] 	= ($f['statusS'] == '1' ? $f['statusS'] : '0');
			$f['date'] 		= strip_tags(trim(mysql_real_escape_string($_POST['cad_data'])));	
			$f['cad_data'] 	= formDate($f['date']);	
		
			if($f['nivel'] == 3){
				if($_POST['premium_end'] > 0){
					$f['premium_end'] = mysql_escape_string($_POST['premium_end']);
					$f['premium_end'] = date('Y-m-d H:i:s',strtotime('+'.$f['premium_end'].'months'));
				}
			}
		
			if(in_array('',$f)){
				echo '<span class="ms in">Você deixou campos em branco, sugerimos que informe todos os campos para uma boa alimentação!</span>';
			}elseif(!valMail($f['email'])){
				echo '<span class="ms al">Atenção: O e-mail informado não tem um formato válido!</span>';
			}elseif(strlen($f['code']) < 8 || strlen($f['code']) > 12){
				echo '<span class="ms no">Erro: Senha deve ter entre 8 e 12 caracteres!</span>';
			}else{
				$readUserMail = read('up_users',"WHERE email = '$f[email]'");
				$readUserCpf  = read('up_users',"WHERE cpf = '$f[cpf]'");
				if($readUserMail){
					echo '<span class="ms no">Erro: Você não pode cadastrar 2 usuários com o mesmo e-mail!</span>';
				}elseif($readUserCpf){
					echo '<span class="ms no">Erro: Você não pode cadastrar 2 usuários com o mesmo CPF!</span>';					
				}else{
					if(!empty($_FILES['avatar']['tmp_name'])){
						$imagem = $_FILES['avatar'];
						$pasta = '../uploads/avatars/';
						$tmp = $imagem['tmp_name'];
						$ext = substr($imagem['name'],-3);
						$nome = md5(time()).'.'.$ext;
						$f['avatar'] = $nome;
						uploadImage($tmp, $nome, '200', $pasta);
					}
					unset($f['date']);
					unset($f['statusS']);
					create('up_users',$f);
					$idLast = mysql_insert_id();
					
					$_SESSION['cadastro'] = '<span class="ms ok">Usuário cadastrado com sucesso! Você pode continuar a editá-lo!</span>';
					header('Location: index2.php?exe=usuarios/usuarios-edit&userid='.$idLast);
				}
			}
		}
	?>
	
	<form name="formulario" action="" method="post"  enctype="multipart/form-data">
		<label class="line">
			<span class="data">Nome:</span>
			<input type="text" name="nome" value="<?php if($f['nome']) echo $f['nome']; ?>" />
		</label>

		<label class="line">
			<span class="data">CPF:</span>
			<input type="text" name="cpf" class="formCpf" value="<?php if($f['cpf']) echo $f['cpf']; ?>" />
		</label>

		<label class="line">
			<span class="data">E-mail:</span>
			<input type="text" name="email" value="<?php if($f['email']) echo $f['email']; ?>" />
		</label>
		
		<label class="line">
			<span class="data">Senha:</span>
			<input type="password" name="senha" value="<?php if($f['code']) echo $f['code']; ?>" />
		</label>

		<label class="line">
			<span class="data">Rua, Número:</span>
			<input type="text" name="rua" value="<?php if($f['rua']) echo $f['rua']; ?>" />
		</label>

		<label class="line">
			<span class="data">Cidade / UF:</span>
			<input type="text" name="cidade" value="<?php if($f['cidade']) echo $f['cidade']; ?>" />
		</label>

		<label class="line">
			<span class="data">CEP:</span>
			<input type="text" name="cep" class="formCep" value="<?php if($f['cep']) echo $f['cep']; ?>" />
		</label>

		<label class="line">
			<span class="data">Telefone:</span>
			<input type="text" name="telefone" class="formFone" value="<?php if($f['telefone']) echo $f['telefone']; ?>" />
		</label>
		
		<label class="line">
			<span class="data">Celular:</span>
			<input type="text" name="celular" class="formCel" value="<?php if($f['celular']) echo $f['celular']; ?>" />
		</label>		
		
		<label class="line">
			<span class="data">Avatar:</span>
			<input type="file" class="fileinput" name="avatar" size="60" style="cursor:pointer; background:#FFF;" />
		</label>

		<div class="line">
		
			<select name="nivel" onChange="
				if(this.value == '3'){
					document.getElementById('ccpremium').style.display = 'block'
				}else{
					document.getElementById('ccpremium').style.display = 'none'
				}
			">
				<option value="">Selecione o nível deste usuário: &nbsp;&nbsp;</option>
				<option <?php if($f['nivel'] && $f['nivel'] == 4) echo 'selected="selected"'; ?> value="4">Leitor &nbsp;&nbsp;</option>
				<option <?php if($f['nivel'] && $f['nivel'] == 3) echo 'selected="selected"'; ?> value="3">Premium &nbsp;&nbsp;</option>
				<option <?php if($f['nivel'] && $f['nivel'] == 2) echo 'selected="selected"'; ?> value="2">Editor &nbsp;&nbsp;</option>
				<option <?php if($f['nivel'] && $f['nivel'] == 1) echo 'selected="selected"'; ?> value="1">Administrador &nbsp;&nbsp;</option>
			</select>
		</div>
		
		<label class="line" id="ccpremium" style="display:none">
			<span class="data">Você selecionou nível PREMIUM, informe a quantidade de MESES para manter o nível:</span>
			<input type="text" class="formMeses" name="premium_end" value="0" style="width:50px; text-align:center;" />
			<span style="font: 12px/30px arial, serif;"><strong style="color:red">Atenção!</strong> Ao cadastrar a quantidade de meses, a data atual será somada a este valor e será considerada para expiração da conta Premium.
			Caso mantenha como <strong>0 (zero)</strong> o nível do usuário será alterado para Leitor!</span>
		</label>
		
		<div class="check">
			<select name="status">
				<option value="">Selecione o status: &nbsp;&nbsp;</option>
				<option <?php if($f['statusS'] && $f['statusS'] == '1') echo 'selected="selected"'; ?> value="1">Ativo &nbsp;&nbsp;</option>
				<option <?php if($f['statusS'] && $f['statusS'] == '-1') echo 'selected="selected"'; ?> value="-1">Inativo &nbsp;&nbsp;</option>
			</select>
		</div>
		
		<label class="line">
			<span class="data">Data do cadastro:</span>
			<input type="text" name="cad_data" class="formDate" value="<?php if($f['date']) echo $f['date']; else echo date('d/m/Y H:i:s'); ?>" />
		</label>
		
		<input type="submit" value="Cadastrar Novo Usuário" name="sendForm" class="btn" />
		
	</form>
		
</div><!-- /bloco form -->
<?php 
	}
}else{
	header('Location: ../index2.php');
}
?>