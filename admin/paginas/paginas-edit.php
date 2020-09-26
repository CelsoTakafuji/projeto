<?php
if(function_exists(getUser)){
	if(!getUser($_SESSION['autUser']['id'],'1')){
		echo '<span class="ms al">Desculpe, você não tem permissão para gerenciar as páginas!</span>';
	}else{
		$urledit  = $_GET['editid'];
		$readEdit = read('up_posts',"WHERE id = '$urledit'");
		if(!$readEdit){
			header('Loncation: index2.php?exe=posts/posts');
		}else
			foreach($readEdit as $postedit);
?>
<div class="bloco form" style="display:block">
	<div class="titulo">
		Editar página:  <strong style="color:#900"><?php echo $postedit['titulo']; ?></strong>
		<a href="index2.php?exe=paginas/paginas" title="Páginas" class="btnalt" style="float:right">Listar Páginas</a>
	</div>
	<form name="formulario" action="" method="post" enctype="multipart/form-data">
		<?php
			if(isset($_POST['sendForm'])){
				$f['titulo'] 	= htmlspecialchars(mysql_real_escape_string($_POST['titulo']));
				$f['tags'] 		= htmlspecialchars(mysql_real_escape_string($_POST['tags']));
				$f['content'] 	= mysql_real_escape_string($_POST['content']);
				$f['date'] 		= htmlspecialchars(mysql_real_escape_string($_POST['data']));
				$f['categoria'] = '0';
				$f['nivel'] 	= '0';
				$f['status']    = '1';
				$f['autor']     = $_SESSION['autUser']['id'];
				$f['tipo']      = 'pagina';
				
				if(in_array('', $f)){
					echo '<span class="ms in">Para uma boa alimentação, informe todos os campos!</span>';
				}else{
					$f['data'] = formDate($f['date']); unset($f['date']);
					if($postedit['titulo'] != $f['titulo']){
						$f['url']  = setUri($f['titulo']);
						$readPostUri = read('up_posts',"WHERE url LIKE '%$f[url]%' AND id != '$urledit'");
						if($readPostUri){
							$f['url'] = $f['url'].'-'.count($readPostUri);
							$readPostUri = read('up_posts',"WHERE url = '$f[url]' AND id != '$urledit'");
							if($readPostUri){
								$f['url'] = $f['url'].'_'.time;
							}
						}
					}else{
						$f['url']  = $postedit['url'];
					}
					update('up_posts',$f,"id = '$urledit'");
					$_SESSION['return'] = '<span class="ms ok">Sua página foi atualizada com sucesso. Para visualizar clique <a href="'.BASE.'/sessao/'.$f['url'].'" target="_blank" title="Ver página aqui">aqui</a>!</span>';
					header('Location: index2.php?exe=paginas/paginas-edit&editid='.$urledit);
				}
			}elseif(!empty($_SESSION['return'])){
					echo $_SESSION['return'];
					unset($_SESSION['return']);
			}elseif(!empty($_SESSION['cadastro'])){
					echo $_SESSION['cadastro'];
					unset($_SESSION['cadastro']);
			}
		?>
		
		<label class="line">
			<span class="data">Nome da página:</span>
			<input type="text" name="titulo" value="<?php echo $postedit['titulo']; ?>" />
		</label>

		<label class="line">
			<span class="data">Tags:</span>
			<input type="text" name="tags" value="<?php echo $postedit['tags']; ?>" />
		</label>
		
		<label class="line">
			<span class="data">Conteúdo:</span>
			<textarea name="content" class="editor" rows="15"><?php echo htmlspecialchars($postedit['content']); ?></textarea>
		</label>

		<label class="line">
			<span class="data">Data:</span>
			<input name="data" class="formDate" value="<?php echo date('d/m/Y H:i:s',strtotime($postedit['data'])); ?>" />
		</label>

		<input type="submit" value="Atualizar página" name="sendForm" class="btn" />
		
	</form>
		
</div><!-- /bloco form -->
<?php 
	}
}else{
	header('Location: ../index2.php');
}
?>