<?php
if(function_exists(getUser)){
	if(!getUser($_SESSION['autUser']['id'],'1')){
		echo '<span class="ms al">Desculpe, você não tem permissão para gerenciar as categorias!</span>';
	}else{
?>
<div class="bloco cat" style="display:block">
	<div class="titulo">
		Categorias:
		<a href="index2.php?exe=posts/categorias-create" title="Criar nova categoria" class="btn" style="float:right">Criar categoria</a>
	</div>
	<?php 
		if(!empty($_GET['delcat'])){
			$idDel = mysql_real_escape_string($_GET['delcat']);
			$readDelCat = read('up_cats',"WHERE id_pai = '$idDel'");
			if(!$readDelCat){
				$del = delete('up_cats',"id = '$idDel'");
				echo '<span class="ms ok">Categoria removida com sucesso!</span>';
			}else{
				echo '<span class="ms al">Esta categoria possui subcategorias. Para excluir é necessário remove-las antes.</span>';
			}
		}
		if(!empty($_GET['delsub'])){
			$idDel = mysql_real_escape_string($_GET['delsub']);
			$del = delete('up_cats',"id = '$idDel'");
			echo '<span class="ms ok">Categoria removida com sucesso!</span>';
		}	
		$pag = (empty($_GET['pag'])? '1' : $_GET['pag']);
		$maximo = 3;
		$inicio = ($pag * $maximo) - $maximo;
		$readCat = read('up_cats',"WHERE id_pai IS null LIMIT $inicio, $maximo");
		if(!$readCat){
			echo '<span class="ms in">Não existem registros de categorias ainda!</span>';
		}else{
			echo '<table width="560" border="0" class="tbdados" style="float:left;" cellspacing="0" cellpadding="0">
				  <tr class="ses">
					<td>categoria:</td>
					<td>resumo:</td>
					<td align="center">tags:</td>
					<td align="center">criada:</td>
					<td align="center" colspan="3">ações:</td>
				  </tr>';

		foreach($readCat as $cat):
			$catTags = ($cat['tags'] != '' ? 'ok.png' : 'no.png');
			echo '<tr>';
			echo '<td>'.$cat['nome'].'</td>';
			echo '<td>'.lmWord($cat['content'],'35').'</td>';
			echo '<td align="center"><img src="ico/'.$catTags.'" alt="Tags da categoria" title="'.$cat['tags'].'" /></td>';
			echo '<td align="center">'.date('d/m/Y H:i',strtotime($cat['data'])).'</td>';
			echo '<td align="center"><a href="index2.php?exe=posts/categorias-edit&edit='.$cat['id'].'" ';
				echo 'title="editar categoria '.$cat['nome'].'"><img src="ico/edit.png" alt="editar categoria '.$cat['nome'].'" ';
				echo 'title="editar categoria '.$cat['nome'].'" /></a></td>';
			echo '<td align="center"><a href="index2.php?exe=posts/categorias-subcreate&idpai='.$cat['id'].'&uri='.$cat['url'].'" ';
				echo 'title="Criar sub categoria"><img src="ico/new.png" alt="Criar sub categoria" ';
				echo 'title="Criar sub categoria" /></a></td>';
			echo '<td align="center"><a href="index2.php?exe=posts/categorias&delcat='.$cat['id'].'" ';
				echo 'title="deletar categoria '.$cat['nome'].'"><img src="ico/no.png" alt="deletar categoria '.$cat['nome'].'" ';
				echo 'title="deletar categoria '.$cat['nome'].'" /></a></td>';
			echo '</tr>';
			
		$readSubCat = read('up_cats',"WHERE id_pai = '$cat[id]'");
		if($readSubCat){
			foreach($readSubCat as $catSub):
			$catSubTags = ($catSub['tags'] != '' ? 'ok.png' : 'no.png');
			echo '<tr class="subcat">';
			echo '<td>&raquo;&raquo; '.$catSub['nome'].'</td>';
			echo '<td>'.lmWord($catSub['content'],'35').'</td>';
			echo '<td align="center"><img src="ico/'.$catSubTags.'" alt="Tags da subcategoria" title="'.$catSub['tags'].'" /></td>';
			echo '<td align="center">'.date('d/m/Y H:i',strtotime($catSub['data'])).'</td>';
			echo '<td align="center"  colspan="2"><a href="index2.php?exe=posts/categorias-edit&edit='.$catSub['id'].'&uri='.$cat['url'].'" ';
				echo 'title="editar categoria '.$catSub['nome'].'"><img src="ico/edit.png" alt="editar categoria '.$catSub['nome'].'" ';
				echo 'title="editar categoria '.$catSub['nome'].'" /></a></td>';
			echo '<td align="center"><a href="index2.php?exe=posts/categorias&delsub='.$catSub['id'].'" ';
				echo 'title="deletar categoria '.$catSub['nome'].'"><img src="ico/no.png" alt="deletar categoria '.$catSub['nome'].'" ';
				echo 'title="deletar categoria '.$catSub['nome'].'" /></a></td>';
			echo '</tr>';
			endforeach;
		}
		endforeach;
		echo '</table>';
		$link = 'index2.php?exe=posts/categorias&pag=';
		readPaginator('up_cats', "WHERE id_pai IS null", $maximo, $link, $pag);
		}
	  ?>
</div><!-- /bloco cat -->
<?php 
	}
}else{
	header('Location: ../index2.php');
}
?>