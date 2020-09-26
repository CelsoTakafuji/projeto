<?php
if(function_exists(getUser)){
	if(!getUser($_SESSION['autUser']['id'],'1')){ 
		echo getUser($_SESSION['autUser']['id'],'1');
		echo '<span class="ms al">Desculpe, você não tem permissão para gerenciar os usuários!</span>';
	}else{
		$userId = $_SESSION['autUser']['id'];
		
		if(isset($_POST['sendFiltro'])){
			$search = $_POST['search'];
			if(!empty($search) && $search != 'Nome:'){
				$_SESSION['whereU'] = "AND nome LIKE '%$search%'";
				header('Location: index2.php?exe=usuarios/usuarios');
			}else{
				unset($_SESSION['whereU']);
				header('Location: index2.php?exe=usuarios/usuarios');
			}
		}
?>
<div class="bloco user" style="display:block">
	<div class="titulo">Usuários:
	
		<form name="filtro" action="" method="post">
			<label>
				<input type="text" name="search" class="radius" size="30" value="Nome:" 
				onclick="if(this.value=='Nome:')this.value=''" 
				onblur="if(this.value=='')this.value='Nome:'"
				/>
			</label>
			<input type="submit" value="Filtrar resultados" name="sendFiltro" class="btn" />
		</form>
	
	</div>   
	<?php
		if(!empty($_GET['deluser'])){
			$delUserId = $_GET['deluser'];
			$readDelUser = read('up_users', "WHERE id = '$delUserId'");
			if(!$readDelUser){
				echo '<span class="ms no">Erro: Usuário não existe!</span>';
			}else{
				foreach($readDelUser as $delUser);
				if($delUser['id'] == $userId){
					echo '<span class="ms no">Erro: Você não pode remover seu perfil!</span>';
				}elseif($delUser['nivel'] == '1'){
					echo '<span class="ms no">Erro: Você não pode remover um administrador!</span>';
				}else{
					echo '<span class="ms al">Atenção você está excluindo o usuário <strong>'.strtoupper($delUser['nome']).'</strong>.<br /> Deseja continuar?';
					echo '[<a href="index2.php?exe=usuarios/usuarios" title="Não">Não</a>] , [<a href="index2.php?exe=usuarios/usuarios&delusertrue='.$delUser['id'].'" title="Sim">Sim</a>]</span>';
				}
			}
		}

		if(!empty($_GET['delusertrue'])){
			$delusertrue = $_GET['delusertrue'];
			$readDelAvatar = read('up_users', "WHERE id = '$delusertrue'");
			foreach($readDelAvatar as $delAvatar);
			$pasta = '../uploads/avatars/';
			if(file_exists($pasta.$delAvatar['avatar']) && !is_dir($pasta.$delAvatar['avatar'])){
				unlink($pasta.$delAvatar['avatar']);
			}
			delete('up_users', "id = '$delusertrue'");
			header('Location: index2.php?exe=usuarios/usuarios');
		}
	
		$pag = (empty($_GET['pag'])? '1' : $_GET['pag']);
		$maximo = 10;
		$inicio = ($pag * $maximo) - $maximo;
		$readUser = read('up_users',"WHERE id != '$userId' {$_SESSION[whereU]} ORDER BY nivel ASC, nome ASC LIMIT $inicio, $maximo");
		
		if(!$readUser){
			echo '<span class="ms in">Não existem registros de usuários!</span>';
		}else{
			echo '<table width="560" border="0" class="tbdados" style="float:left;" cellspacing="0" cellpadding="0">
		  <tr class="ses">
			<td align="center">#id</td>
			<td>nome:</td>
			<td>email:</td>
			<td align="center">nível:</td>
			<td align="center" colspan="3">ações:</td>
		  </tr>';

			foreach($readUser as $user):
				$nivel = ($user['nivel'] == 1 ? 'Admin' : ($user['nivel'] == 2 ? 'Editor' : ($user['nivel'] == 3 ? 'Premium' : 'Leitor')));
				echo '<tr>';
				echo '<td align="center">'.$user['id'].'</td>';
				echo '<td>'.$user['nome'].'</td>';
				echo '<td>'.$user['email'].'</td>';
				echo '<td align="center">'.$nivel.'</td>';
				echo '<td align="center"><a href="index2.php?exe=usuarios/usuarios-edit&userid='.$user['id'].'" title="editar"><img src="ico/edit.png" alt="editar" title="editar" /></a></td>';
				echo '<td align="center"><a href="index2.php?exe=usuarios/usuarios&deluser='.$user['id'].'" title="excluir"><img src="ico/no.png" alt="excluir" title="excluir" /></a></td>';
				echo '</tr>';
			endforeach;
			echo '</table>';
			$link = 'index2.php?exe=usuarios/usuarios&pag=';
			readPaginator('up_users', "WHERE id != '$userId' {$_SESSION[whereU]} ORDER BY nivel ASC, nome ASC", $maximo, $link, $pag);
		}
	?>
</div><!-- /bloco user -->
<?php 
	}
}else{
	header('Location: ../index2.php');
}
?>