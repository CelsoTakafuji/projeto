<?php
if(function_exists(getUser)){
	if(!getUser($_SESSION['autUser']['id'],'1')){
		echo '<span class="ms al">Desculpe, você não tem permissão para gerenciar as páginas!</span>';
	}else{
		
?>
<div class="bloco list" style="display:block">
	<div class="titulo">Páginas:</div><!-- /titulo -->
	
	<?php
		//REMOVE O PÁGINAS
		if(!empty($_GET['delid'])){
			$delId = $_GET['delid'];
			$pasta = '../uploads/';
			$readGbDel = read('up_posts_gb',"WHERE post_id = '$delId'");
			if($readGbDel){
				foreach($readGbDel as $gbDel):
					if(file_exists($pasta.$gbDel['img']) && !is_dir($pasta.$gbDel['img'])){
						unlink($pasta.$gbDel['img']);
					}
					delete('up_posts_gb',"post_id = '$delId'");
				endforeach;
			}
			delete('up_posts',"id = '$delId'");
		}
		
		$pag = (empty($_GET['pag'])? '1' : $_GET['pag']);
		$maximo = 10;
		$inicio = ($pag * $maximo) - $maximo;
		$readArt = read('up_posts',"WHERE tipo = 'pagina' {$_SESSION[where]} ORDER BY data DESC LIMIT $inicio, $maximo");
		if(!$readArt){
			echo '<span class="ms in">Não existem registros de páginas ainda!</span>';
		}else{
			echo '<table width="560" border="0" class="tbdados" style="float:left;" cellspacing="0" cellpadding="0">
				  <tr class="ses">
					<td>Nome:</td>
					<td align="center">Resumo:</td>
					<td align="center">Tags:</td>
					<td align="center">Criada:</td>
					<td align="center" colspan="3">Ações:</td>
				  </tr>';
			foreach($readArt as $art):
				$stIco = ($art['tags'] == '' ? 'alert.png' : 'ok.png');
				echo '<tr>';
				echo '<td><a href="'.BASE.'/sessao/'.$art[url].'" title="'.$art['titulo'].'" target="_blank">'.lmWord($art['titulo'],20).'</a></td>';
				echo '<td>'.lmWord($art['content'],30).'</td>';
				echo '<td align="center"><img src="ico/'.$stIco.'" alt="'.$art['tags'].'" title="'.$art['tags'].'" /></td>';
				echo '<td align="center">'.date('d/m/y H:i',strtotime($art['data'])).'</td>';
				echo '<td align="center"><a href="index2.php?exe=paginas/paginas-edit&editid='.$art['id'].'" title="editar"><img src="ico/edit.png" alt="editar" title="editar" /></a></td>';
				echo '<td align="center"><a href="index2.php?exe=paginas/gallery&postid='.$art['id'].'" title="postar galeria"><img src="ico/gb.png" alt="postar galeria" title="postar galeria" /></a></td>';
				echo '<td align="center"><a href="index2.php?exe=paginas/paginas&pag='.$pag.'&delid='.$art['id'].'" title="excluir"><img src="ico/no.png" alt="excluir" title="excluir" /></a></td>';
				echo '</tr>';
			endforeach; 
			echo '</table>';
			$link = 'index2.php?exe=paginas/paginas&pag=';
			readPaginator('up_posts', "WHERE tipo = 'pagina' {$_SESSION[where]} ORDER BY data DESC", $maximo, $link, $pag);
		}
	?>
</div><!-- /bloco list -->
<?php 
	}
}else{
	header('Location: ../index2.php');
}
?>