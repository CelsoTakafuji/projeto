<?php
if(function_exists(getUser)){
	if(!getUser($_SESSION['autUser']['id'],'2')){
		header('Location: index2.php');
	}else{
		$userEditId = $_GET['userid'];
		$readEditId = read('up_users',"WHERE id = '$userEditId'");
		if(!readEditId){
			header('Location: index2.php?exe=usuarios/usuarios');
		}elseif($_SESSION['autUser']['nivel'] != '1'){
			foreach($readEditId as $user);
			if($_SESSION['autUser']['id'] != $user['id']){
				header('Location: index2.php?exe=usuarios/usuarios');
			}
		}else{
			foreach($readEditId as $user);
			$status = ($user['status'] == 1 ? $user['status'] : '-1');
			$display_premium = ($user['nivel'] == 3 ? true : false);
		}
?>
<div class="bloco form" style="display:block">
	<div class="titulo">
		Editar Usuário: <strong style="color:#900"><?php echo $user['nome'] ?></strong>
		<?php if($_SESSION['autUser']['nivel'] == '1'){ ?>
		<a href="index2.php?exe=usuarios/usuarios" style="float:right" class="btnalt" title="Voltar">Voltar</a>
		<?php }else{ ?>
		<a href="index2.php" style="float:right" class="btnalt" title="Voltar">Voltar</a>
		<?php } ?>
	</div>
	
	<?php
		if(isset($_POST['sendForm'])){
			$f['nome'] 		= strip_tags(trim(mysql_real_escape_string($_POST['nome'])));
			$f['cpf'] 		= strip_tags(trim(mysql_real_escape_string($_POST['cpf'])));
			$f['cpf']		= ($f['cpf'] != '' ? $f['cpf'] : $user['cpf']);
			$f['email'] 	= strip_tags(trim(mysql_real_escape_string($_POST['email'])));
			$f['email']		= ($f['email'] != '' ? $f['email'] : $user['email']);
			$f['code'] 		= strip_tags(trim(mysql_real_escape_string($_POST['senha'])));
			$f['senha'] 	= md5($f['code']);
			$f['rua'] 		= strip_tags(trim(mysql_real_escape_string($_POST['rua'])));
			$f['cidade'] 	= strip_tags(trim(mysql_real_escape_string($_POST['cidade'])));
			$f['cep'] 		= strip_tags(trim(mysql_real_escape_string($_POST['cep'])));
			$f['telefone'] 	= strip_tags(trim(mysql_real_escape_string($_POST['telefone'])));
			$f['celular'] 	= strip_tags(trim(mysql_real_escape_string($_POST['celular'])));
			$f['nivel'] 	= strip_tags(trim(mysql_real_escape_string($_POST['nivel'])));
			$f['nivel']		= ($f['nivel'] != '' ? $f['nivel'] : $user['nivel']);
			$f['statusS'] 	= strip_tags(trim(mysql_real_escape_string($_POST['status'])));
			$f['statusS']	= ($f['statusS'] != '' ? $f['statusS'] : $user['status']);
			$f['status'] 	= ($f['statusS'] == '1' ? $f['statusS'] : '0');
			$f['date'] 		= strip_tags(trim(mysql_real_escape_string($_POST['cad_data'])));	
			$f['date']		= ($f['date'] != '' ? $f['date'] : date('d/m/Y H:i:s',strtotime($user['cad_data'])));
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
				if(!empty($_FILES['avatar']['tmp_name'])){
					$imagem = $_FILES['avatar'];
					$pasta = '../uploads/avatars/';
					if(file_exists($pasta.$user['avatar']) && !is_dir($pasta.$user['avatar'])){
						unlink($pasta.$user['avatar']);
					}
					$tmp = $imagem['tmp_name'];
					$ext = substr($imagem['name'],-3);
					$nome = md5(time()).'.'.$ext;
					$f['avatar'] = $nome;
					uploadImage($tmp, $nome, '200', $pasta);
				}
				unset($f['date']);
				unset($f['statusS']);
				update('up_users',$f,"id = '$userEditId'");
				$_SESSION['return'] = '<span class="ms ok">Usuário atualizado com sucesso!</span>';
				header('Location: index2.php?exe=usuarios/usuarios-edit&userid='.$userEditId);
			}
		}elseif(!empty($_SESSION['return'])){
			echo $_SESSION['return'];
			unset($_SESSION['return']);
		}elseif(!empty($_SESSION['cadastro'])){
			echo $_SESSION['cadastro'];
			unset($_SESSION['cadastro']);
		}
	?>
	
	<form name="formulario" action="" method="post"  enctype="multipart/form-data">
		<label class="line">
			<span class="data">Nome:</span>
			<input type="text" name="nome" value="<?php echo $user['nome']; ?>" />
		</label>

		<?php if($_SESSION['autUser']['nivel'] == '1'){ ?>
		<label class="line">
			<span class="data">CPF:</span>
			<input type="text" name="cpf" class="formCpf" value="<?php echo $user['cpf']; ?>" />
		</label>

		<label class="line">
			<span class="data">E-mail:</span>
			<input type="text" name="email" value="<?php echo $user['email']; ?>" />
		</label>
		<?php } ?>
		
		<label class="line">
			<span class="data">Senha:</span>
			<input type="password" name="senha" value="<?php echo $user['code']; ?>" />
		</label>

		<label class="line">
			<span class="data">Rua, Número:</span>
			<input type="text" name="rua" value="<?php echo $user['rua']; ?>" />
		</label>

		<label class="line">
			<span class="data">Cidade / UF:</span>
			<input type="text" name="cidade" value="<?php echo $user['cidade']; ?>" />
		</label>

		<label class="line">
			<span class="data">CEP:</span>
			<input type="text" name="cep" class="formCep" value="<?php echo $user['cep']; ?>" />
		</label>

		<label class="line">
			<span class="data">Telefone:</span>
			<input type="text" name="telefone" class="formFone" value="<?php echo $user['telefone']; ?>" />
		</label>
		
		<label class="line">
			<span class="data">Celular:</span>
			<input type="text" name="celular" class="formCel" value="<?php echo $user['celular']; ?>" />
		</label>		
		
		<label class="line">
			<span class="data">
				<?php
					if($user['avatar'] != '' && file_exists('../uploads/avatars/'.$user['avatar'])){
						echo '<a href="../uploads/avatars/'.$user['avatar'].'" title="Ver avatar" rel="ShadowBox">';
						echo '<img src="../tim.php?src=../uploads/avatars/'.$user['avatar'].'&w=50&h=50&zc=1&q=100" title="Avatar do usuário" alt="Avatar do usuário" />';
						echo '</a>';
					}else{
						echo 'Avatar: ';
					}
				?>	
			</span>
			<input type="file" class="fileinput" name="avatar" size="60" style="cursor:pointer; background:#FFF;" />
		</label>
		
		<?php if($_SESSION['autUser']['nivel'] == '1'){ ?>
		<div class="line">
		
			<select name="nivel" onChange="
				if(this.value == '3'){
					document.getElementById('ccpremium').style.display = 'block'
				}else{
					document.getElementById('ccpremium').style.display = 'none'
				}
			">
				<option value="">Selecione o nível deste usuário: &nbsp;&nbsp;</option>
				<option <?php if($user['nivel'] && $user['nivel'] == 4) echo 'selected="selected"'; ?> value="4">Leitor &nbsp;&nbsp;</option>
				<option <?php if($user['nivel'] && $user['nivel'] == 3) echo 'selected="selected"'; ?> value="3">Premium &nbsp;&nbsp;</option>
				<option <?php if($user['nivel'] && $user['nivel'] == 2) echo 'selected="selected"'; ?> value="2">Editor &nbsp;&nbsp;</option>
				<option <?php if($user['nivel'] && $user['nivel'] == 1) echo 'selected="selected"'; ?> value="1">Administrador &nbsp;&nbsp;</option>
			</select>
		</div>
		
		<label class="line" id="ccpremium" style="display:<?php echo ($display_premium ? 'block' : 'none'); ?>">
			<span class="data">Você selecionou nível PREMIUM, informe a quantidade de MESES para manter o nível:</span>
			<input type="text" class="formMeses" name="premium_end" value="0" style="width:50px; text-align:center;" />
			<span style="font: 12px/30px arial, serif;"><strong style="color:red">Atenção!</strong> Ao alterar a quantidade de meses, a data atual será somada a este valor e será considerada para expiração da conta Premium. 
			Caso não pretenda alterar e só manter o valor em <strong>0 (zero)</strong> que será mantida a data de expiração que é: 
			<?php echo "<strong style=\"color:grey\">".($user['premium_end'] == '' || date('Y-m-d H:i:s',strtotime($user['premium_end'])) < date('Y-m-d H:i:s') ? 'Não possui ou já expirou!' : date('d/m/Y H:i:s', strtotime($user['premium_end'])))."</strong>"; ?>
			</span>
		</label>

		<div class="check">
			<select name="status">
				<option value="">Selecione o status: &nbsp;&nbsp;</option>
				<option <?php if($status && $status == '1') echo 'selected="selected"'; ?> value="1">Ativo &nbsp;&nbsp;</option>
				<option <?php if($status && $status == '-1') echo 'selected="selected"'; ?> value="-1">Inativo &nbsp;&nbsp;</option>
			</select>
		</div>
		
		<label class="line">
			<span class="data">Data do cadastro:</span>
			<input type="text" name="cad_data" class="formDate" value="<?php echo date('d/m/Y H:i:s', strtotime($user['cad_data'])); ?>" />
		</label>
		<?php } ?>
		<input type="submit" value="Atualizar Usuário" name="sendForm" class="btn" />
		
	</form>
		
</div><!-- /bloco form -->
<?php 
	}
}else{
	header('Location: ../index2.php');
}
?>