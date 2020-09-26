<?php
if(function_exists(getUser)){
	if(!getUser($_SESSION['autUser']['id'],'2')){
		header('Location: index2.php');
	}else{
		$postid = $_GET['postid'];
		$readPost = read('up_posts',"WHERE id = '$postid'");
		if(!$readPost){
			header('Location: index2.php?exe=posts/posts');
		}else
			foreach($readPost as $post);
?>
<div class="bloco form" style="display:block">
	<div class="titulo">
		Posta fotos em: <strong style="color:#900"><?php echo $post['titulo'];  ?></strong>
		<a href="index2.php?exe=posts/posts" title="Artigos" class="btnalt">Voltar</a>
	</div>
	<form action="" method="post">
		<label>
			<input id="file_upload" name="file_upload" type="file" class="file" />
		</label>
			<a href="javascript:$('#file_upload').uploadifyUpload();" class="btn upload">clique aqui para enviar!</a>
	</form>
	<?php 
		if(!empty($_GET['delid'])){
			$imgId = $_GET['delid'];
			$imgIm = $_GET['img'];
			$pasta = '../uploads/';
			if(file_exists($pasta.$imgIm) && !is_dir($pasta.$imgIm)){
				unlink($pasta.$imgIm);
			}
			delete('up_posts_gb',"id = '$imgId'");
		}
		$readGb = read('up_posts_gb',"WHERE post_id = '$postid'");
		if(!$readGb){
			
		}else{
			echo '<ul class="gblist">';
			foreach($readGb as $gb):
			$i++;
	?>
	
		<li<?php if($i%6==0){echo ' class="last"';}?>>
			<img src="../tim.php?src=../uploads/<?php echo $gb['img']; ?>&w=85&h=65&q=100&zc=1" width="85" height="65" />
			<div class="action">
				<a href="../uploads/<?php echo $gb['img']; ?>" rel="shadowbox" title="Imagem da galeria de: <?php echo $post['titulo']; ?>"><img src="ico/view.png" title="Imagem da galeria de: <?php echo $post['titulo']; ?>" alt="Imagem da galeria de: <?php echo $post['titulo']; ?>" /></a>
				<a href="index2.php?exe=posts/gallery&postid=<?php  echo $post['id'] ?>&delid=<?php echo $gb['id']; ?>&img=<?php echo $gb['img']; ?>" title="Exluir imagem: <?php echo $i; ?>"><img src="ico/no.png" title="Exluir imagem: <?php echo $i; ?>" alt="Exluir imagem: <?php echo $i; ?>" /></a>
			</div><!-- /action -->
		</li>

	<?php
			endforeach;
			echo '</ul>';
		}
	?>
</div><!-- /bloco form -->
<?php 
	}
}else{
	header('Location: ../index2.php');
}
?>