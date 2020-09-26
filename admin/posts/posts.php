<?php
if(function_exists(getUser)){
	if(!getUser($_SESSION['autUser']['id'],'2')){
		header('Location: index2.php');
	}else{
		if(isset($_POST['sendFiltro'])){
			$search = $_POST['search'];
			if(!empty($search) && $search != 'Titulo:'){
				$_SESSION['whereP'] = "AND titulo LIKE '%$search%'";
				header('Location: index2.php?exe=posts/posts');
			}else{
				unset($_SESSION['whereP']);
				header('Location: index2.php?exe=posts/posts');
			}
		}
		
?>
<div class="bloco list" style="display:block">
	<div class="titulo">Artigos:
	
		<form name="filtro" action="" method="post">
			<label>
				<input type="text" name="search" class="radius" size="30" value="Titulo:" 
				onclick="if(this.value=='Titulo:')this.value=''" 
				onblur="if(this.value=='')this.value='Titulo:'"
				/>
			</label>
			<input type="submit" value="Filtrar resultados" name="sendFiltro" class="btn" />
		</form>
	
	</div><!-- /titulo -->
	<?php
		//ALTERAR O STATUS DO POST
		if(isset($_GET['sts'])){
			$status = $_GET['sts'];
			$topicoid = $_GET['id'];
			if($status == '0'){
				$datas = array('status' => '1');
				update('up_posts',$datas,"id = '$topicoid'");
			}else{
				$datas = array('status' => '0');
				update('up_posts',$datas,"id = '$topicoid'");
			}
		}
		
		//REMOVE O POST
		if(!empty($_GET['delid'])){
			$delId = $_GET['delid'];
			$thumb = $_GET['thumb'];
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
			if(file_exists($pasta.$thumb) && !is_dir($pasta.$thumb)){
				unlink($pasta.$thumb);
			}
			delete('up_posts',"id = '$delId'");
		}
		
		$pag = (empty($_GET['pag'])? '1' : $_GET['pag']);
		$maximo = 10;
		$inicio = ($pag * $maximo) - $maximo;
		$readArt = read('up_posts',"WHERE tipo = 'post' {$_SESSION[whereP]} ORDER BY data DESC LIMIT $inicio, $maximo");
		if(!$readArt){
			echo '<span class="ms in">Não existem registros de artigos ainda!</span>';
		}else{
			echo '<table width="560" border="0" class="tbdados" style="float:left;" cellspacing="0" cellpadding="0">
				  <tr class="ses">
					<td>Titulo:</td>
					<td align="center">Data:</td>
					<td align="center">Categoria:</td>
					<td align="center">Visitas:</td>
					<td align="center" colspan="4">Ações:</td>
				  </tr>';
			foreach($readArt as $art):
				$views = ($art['visitas'] != '' ? $art['visitas'] : '0');
				$stIco = ($art['status'] == '0' ? 'alert.png' : 'ok.png');
				$stSta = ($art['status'] == '0' ? 'ativar' : 'inativar');
				echo '<tr>';
				echo '<td><a href="'.BASE.'/artigo/'.$art[url].'" title="'.$art['titulo'].'" target="_blank">'.lmWord($art['titulo'],30).'</a></td>';
				echo '<td align="center">'.date('d/m/y H:i',strtotime($art['data'])).'</td>';
				echo '<td align="center"><a target="_blank" href="'.BASE.'/categoria/'.getCat($art['categoria'],'url').'" title="'.getCat($art['categoria'],'url').'">'.getCat($art['categoria'],'nome').'</a></td>';
				echo '<td align="center">'.$views.'</td>';
				echo '<td align="center"><a href="index2.php?exe=posts/posts-edit&editid='.$art['id'].'" title="editar"><img src="ico/edit.png" alt="editar" title="editar" /></a></td>';
				echo '<td align="center"><a href="index2.php?exe=posts/gallery&postid='.$art['id'].'" title="postar galeria"><img src="ico/gb.png" alt="postar galeria" title="postar galeria" /></a></td>';
				echo '<td align="center"><a href="index2.php?exe=posts/posts&pag='.$pag.'&sts='.$art['status'].'&id='.$art['id'].'" title="'.$stSta.'"><img src="ico/'.$stIco.'" alt="'.$stSta.'" title="'.$stSta.'" /></a></td>';
				echo '<td align="center"><a href="index2.php?exe=posts/posts&pag='.$pag.'&delid='.$art['id'].'&thumb='.$art['thumb'].'" title="excluir"><img src="ico/no.png" alt="excluir" title="excluir" /></a></td>';
				echo '</tr>';
			endforeach; 
			echo '</table>';
			$link = 'index2.php?exe=posts/posts&pag=';
			readPaginator('up_posts', "WHERE tipo = 'post' {$_SESSION[whereP]} ORDER BY data DESC", $maximo, $link, $pag);
		}
	?>
</div><!-- /bloco list -->
<?php 
	}
}else{
	header('Location: ../index2.php');
}
?>