<?php
if(function_exists(getUser)){
	if(!getUser($_SESSION['autUser']['id'],'1')){
		echo '<span class="ms al">Desculpe, você não tem permissão para gerenciar as categorias!</span>';
	}else{
?>
<div class="bloco form" style="display:block">
	<div class="titulo">
		Criar categoria:
		<a href="index2.php?exe=posts/categorias" title="Voltar" class="btnalt" style="float:right">Voltar</a>
	</div>
	
	<?php
		if(isset($_POST['sendForm'])){
			$f['nome'] 		= htmlspecialchars(mysql_escape_string($_POST['nome']));
			$f['content'] 	= htmlspecialchars(mysql_escape_string($_POST['content']));
			$f['tags'] 		= htmlspecialchars(mysql_escape_string($_POST['tags']));
			$f['date'] 		= htmlspecialchars(mysql_escape_string($_POST['data']));
		
			if(in_array('',$f)){
				echo '<span class="ms in">Para uma boa alimentação, preencha todos os campos!</span>';
			}else{
				$f['data'] = formDate($f['date']); unset($f['date']);
				$f['url']  = setUri($f['nome']);
				$readCatUri = read('up_cats',"WHERE url LIKE '%$f[url]%'");
				if($readCatUri){
					$f['url'] = $f['url'].'-'.count($readCatUri);
					$readCatUri = read('up_cats',"WHERE url = '$f[url]'");
					if($readCatUri){
						$f['url'] = $f['url'].'_'.time;
					}
				}
				create('up_cats',$f);
				$idLast = mysql_insert_id();
				
				$_SESSION['cadastro'] = '<span class="ms ok">Categoria criada com sucesso! Você pode continuar a editá-la!</span>';
				header('Location: index2.php?exe=posts/categorias-edit&edit='.$idLast);
			}
		}
	?>
	
	<form name="formulario" action="" method="post">
		<label class="line">
			<span class="data">Nome:</span>
			<input type="text" name="nome" value="<?php if($f['nome']) echo $f['nome'];?>" />
		</label>

		<label class="line">
			<span class="data">Descrição:</span>
			<textarea name="content" rows="3"><?php if($f['content']) echo $f['content'];?></textarea>
		</label>
		
		<label class="line">
			<span class="data">Tags:</span>
			<input type="text" name="tags" value="<?php if($f['tags']) echo $f['tags'];?>" />
		</label>
		
		<label class="line">
			<span class="data">Data:</span>
			<input type="text" class="formDate" name="data" value="<?php if($f['date']){ echo $f['date']; }else{ echo date('d/m/Y H:i:s'); }?>" />
		</label>

		<input type="submit" value="Criar Categoria" name="sendForm" class="btn" />
	</form>
		
</div><!-- /bloco form -->
<?php 
	}
}else{
	header('Location: ../index2.php');
}
?>