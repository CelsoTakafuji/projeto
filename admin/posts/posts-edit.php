<?php
if(function_exists(getUser)){
	if(!getUser($_SESSION['autUser']['id'],'2')){
		header('Location: index2.php');
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
		Editar artigo: <strong style="color:#900"><?php echo $postedit['titulo']; ?></strong>
	</div>
	<form name="formulario" action="" method="post" enctype="multipart/form-data">
		<?php
			if(isset($_POST['sendForm'])){
				$f['titulo'] 	= htmlspecialchars(mysql_real_escape_string($_POST['titulo']));
				$f['tags'] 		= htmlspecialchars(mysql_real_escape_string($_POST['tags']));
				$f['content'] 	= mysql_real_escape_string($_POST['content']);
				$f['date'] 		= htmlspecialchars(mysql_real_escape_string($_POST['data']));
				$f['categoria'] = htmlspecialchars(mysql_real_escape_string($_POST['categoria']));
				$f['cat_pai']   = getCat($f['categoria'], 'id_pai');
				$f['nivel'] 	= htmlspecialchars(mysql_real_escape_string($_POST['nivel']));
				$f['status']    = ($_POST['sendForm'] == 'Salvar' ? '0' : '1');
				$f['autor']     = $_SESSION['autUser']['id'];
				$f['tipo']      = 'post';
				
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
					
					if(!empty($_FILES['thumb']['tmp_name'])){
						$pasta = '../uploads/';
						$ano   = date('Y');
						$mes   = date('m');
						if(file_exists($pasta.$postedit['thumb']) && !is_dir($pasta.$postedit['thumb'])){
							unlink($pasta.$postedit['thumb']);
						}
						if(!file_exists($pasta.$ano)){
							mkdir($pasta.$ano,0755);
						}
						if(!file_exists($pasta.$ano.'/'.$mes)){
							mkdir($pasta.$ano.'/'.$mes,0755);
						}
						$img = $_FILES['thumb'];
						$ext = substr($img['name'],-3);
						$f['thumb'] = $ano.'/'.$mes.'/'.$f['url'].'.'.$ext;
						uploadImage($img['tmp_name'], $f['url'].'.'.$ext, '960', $pasta.$ano.'/'.$mes.'/');
					}
					update('up_posts',$f,"id = '$urledit'");
					$_SESSION['return'] = '<span class="ms ok">Seu artigo foi atualizado com sucesso. Visualizar <a href="'.BASE.'/artigo/'.$f['url'].'" target="_blank" title="Ver artigo aqui">aqui</a>!</span>';
					header('Location: index2.php?exe=posts/posts-edit&editid='.$urledit);
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
			<span class="data">Foto de exibição:</span>
			<input type="file" class="fileinput" name="thumb" size="60" style="cursor:pointer; background:#FFF;" />
		</label>
		
		<label class="line">
			<span class="data">Titulo:</span>
			<input type="text" name="titulo" value="<?php echo $postedit['titulo']; ?>" />
		</label>

		<label class="line">
			<span class="data">Tags:</span>
			<input type="text" name="tags" value="<?php echo $postedit['tags']; ?>" />
		</label>
		
		<label class="line">
			<span class="data">Conteúdo:</span>
			<textarea name="content" class="editor" rows="15"><?php echo htmlspecialchars(stripcslashes($postedit['content'])); ?></textarea>
		</label>

		<label class="line">
			<span class="data">Data:</span>
			<input name="data" class="formDate" value="<?php echo date('d/m/Y H:i:s',strtotime($postedit['data'])); ?>" />
		</label>

		<label class="line">
			<select name="categoria">
				<option value="">Selecione uma categoria &nbsp;&nbsp;</option>
				<?php
					$readCategoriaPai = read('up_cats',"WHERE id_pai IS NULL");
					if(!$readCategoriaPai){
						echo '<option value="" disabled="disabled">Não encontramos categorias. &nbsp;&nbsp;</option>';
					}else{
						foreach($readCategoriaPai as $pai):
							echo '<option value="" disabled="disabled">'.$pai['nome'].'</option>';
							$readCategorias = read('up_cats',"WHERE id_pai = '$pai[id]'");
							if(!$readCategorias){
								echo '<option value="" disabled="disabled">Cadastre uma categoria aqui! &nbsp;&nbsp;</option>';
							}else{
								foreach($readCategorias as $cat):
									echo '<option value="'.$cat[id].'" ';
									if($cat['id'] == $postedit['categoria']){
										echo 'selected="selected"';
									}
									echo '>&raquo;&raquo; '.$cat['nome'].'</option>';
								endforeach;
							}
						endforeach;
					}
				?>
			</select>
		</label>		

		<div class="check">
			<span class="data">Permissão do artigo:</span>
			
			<ul>
				<li><label><input type="radio" value="0" name="nivel" <?php if(!$postedit['nivel'] || $postedit['nivel'] == '0') echo 'checked="checked"'; ?> /> Livre</label></li>
				<li><label><input type="radio" value="4" name="nivel" <?php if($postedit['nivel'] && $postedit['nivel'] == '4') echo 'checked="checked"'; ?>/> Leitor</label></li>
				<li class="last"><label><input type="radio" value="3" name="nivel" <?php if($postedit['nivel'] && $postedit['nivel'] == '3') echo 'checked="checked"'; ?>/> Premium</label></li>
			</ul>
		</div>

		<a href="index2.php?exe=posts/posts" title="Artigos" class="btnalt">Listar Artigos</a>
		<input type="submit" value="Atualizar Artigo" name="sendForm" class="btn" />
		
	</form>
		
</div><!-- /bloco form -->
<?php 
	}
}else{
	header('Location: ../index2.php');
}
?>