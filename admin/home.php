<div class="bloco home" style="display:block">
	<div class="titulo">Estatísticas do site:</div>
	
	<?php
		$tres = date('m/Y',strtotime('-3months'));
		$tresEx = explode('/',$tres);
		$readTres = read('up_views', "WHERE mes = '$tresEx[0]' AND ano = '$tresEx[1]'");
		if($readTres){
			foreach($readTres as $rTres);
			$usersT = $rTres['visitantes'];
			$viewsT = $rTres['visitas'];
			$pagesT = substr($rTres['pageviews']/$rTres['visitas'],0,5);
		}else{
			$usersT = '0';
			$viewsT = '0';
			$pagesT = '0';
		}
		
		$dois = date('m/Y',strtotime('-2months'));
		$doisEx = explode('/',$dois);
		$readDois = read('up_views', "WHERE mes = '$doisEx[0]' AND ano = '$doisEx[1]'");
		if($readDois){
			foreach($readDois as $rDois);
			$usersD = $rDois['visitantes'];
			$viewsD = $rDois['visitas'];
			$pagesD = substr($rDois['pageviews']/$rDois['visitas'],0,5);
		}else{
			$usersD = '0';
			$viewsD = '0';
			$pagesD = '0';
		}
		
		$um = date('m/Y',strtotime('-1months'));
		$umEx = explode('/',$um);
		$readUm = read('up_views', "WHERE mes = '$umEx[0]' AND ano = '$umEx[1]'");
		if($readUm){
			foreach($readUm as $rUm);
			$usersU = $rUm['visitantes'];
			$viewsU = $rUm['visitas'];
			$pagesU = substr($rUm['pageviews']/$rUm['visitas'],0,5);
		}else{
			$usersU = '0';
			$viewsU = '0';
			$pagesU = '0';
		}
		
		$atual = date('m/Y');
		$atualEx = explode('/',$atual);
		$readAtual = read('up_views', "WHERE mes = '$atualEx[0]' AND ano = '$atualEx[1]'");
		if($readAtual){
			foreach($readAtual as $rAtual);
			$users = $rAtual['visitantes'];
			$views = $rAtual['visitas'];
			$pages = substr($rAtual['pageviews']/$rAtual['visitas'],0,5);
		}else{
			$users = '0';
			$views = '0';
			$pages = '0';
		}
	?>
	
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
	  google.load("visualization", "1", {packages:["corechart"]});
	  google.setOnLoadCallback(drawChart);
	  function drawChart() {
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Year');
		data.addColumn('number', 'Usuários');		
		data.addColumn('number', 'Visitas');
		data.addColumn('number', 'PageViews');

		data.addRows([
		  ['<?php echo $tres; ?>', <?php echo $usersT; ?>, <?php echo $viewsT; ?>, <?php echo $pagesT; ?>],
		  ['<?php echo $dois; ?>', <?php echo $usersD; ?>, <?php echo $viewsD; ?>, <?php echo $pagesD; ?>],
		  ['<?php echo $um;   ?>', <?php echo $usersU; ?>, <?php echo $viewsU; ?>, <?php echo $pagesU; ?>],
		  ['<?php echo $atual;?>', <?php echo $users;  ?>, <?php echo $views;  ?>, <?php echo $pages;  ?>]
		]);

		var options = {
		  width: 554, height: 200,
		  title: 'Visitas em seu site:',
		  hAxis: {title: 'relátorio de 3 meses', titleTextStyle: {color: 'red'}},
		  pointSize: 8,
		  focusTarget: 'category'
		};

		var chart = new google.visualization.LineChart(document.getElementById('chart_divDois'));
		chart.draw(data, options);
	  }
	</script>
	<div id="chart_divDois" style="width:554px; height:200px; float:left; border:3px solid #CCC; margin-bottom:15px;"></div>
	
	<table width="200" border="0" class="tbdados" style="float:right;" cellspacing="0" cellpadding="0">
	  <tr class="ses">
		<td>Usuários cadastrados:</td>
		<td align="center">
			<?php
				$readUserCad = read('up_users');
				echo count($readUserCad);
			?>
		</td>
	  </tr>
	  <tr>
		<td>Visitantes Online:</td>
		<td align="center">
			<?php
				$readVisitantes = read('up_views_online');
				echo count($readVisitantes);
			?>
		</td>
	  </tr>
	  <tr class="ses">
		<td colspan="2">Sessões:</td>
	  </tr>
	  <tr>
		<td>Categorias:</td>
		<td align="center">
			<?php
				$readCategorias = read('up_cats');
				echo count($readCategorias);
			?>
		</td>
	  </tr>
	  <tr>
		<td>Páginas:</td>
		<td align="center">
			<?php
				$readPaginas = read('up_posts',"WHERE tipo = 'pagina'");
				echo count($readPaginas);
			?>
		</td>
	  </tr>
	</table>
	
	<?php
		$countViews = "SELECT SUM(visitas) AS views FROM up_posts";
		$exeViews = mysql_query($countViews) or die ('Erro ao contar visitas');
		$views = 0;
		$visitas = mysql_result($exeViews, $views, "views");
		if($visitas >= 1){
			$visitas = $visitas;
		}else{
			$visitas = 0;
		}
		
		$artigosCount = read('up_posts',"WHERE tipo = 'post'");
		$countArtigos = count($artigosCount);
	?>
	
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
	  google.load("visualization", "1", {packages:["corechart"]});
	  google.setOnLoadCallback(drawChart);
	  function drawChart() {
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Task');
		data.addColumn('number', 'Visitas totais');
		data.addRows([
		  ['Artigos', <?php echo $countArtigos; ?>],
		  ['Visitas em artigos', <?php echo $visitas; ?>],
		  ['Média por artigo', <?php echo substr($visitas/$countArtigos,0,5); ?>]
		]);

		var options = {
		  title: 'Visitas em seus artigos:'
		};

		var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
		chart.draw(data, options);
	  }
	</script>
	<div id="chart_div" style="width:345px; height:141px; float:left; border:3px solid #CCC;"></div>
	
	<div class="sub" style="margin-top:15px;">Artigos:</div>
	
	<table width="270" border="0" class="tbdados" style="float:left;" cellspacing="0" cellpadding="0">
	  <tr class="ses">
		<td>Últimas atualizações</td>
		<td align="center">Data</td>
	  </tr>
	  <?php 
		$readArtUtl = read('up_posts',"WHERE tipo = 'post' ORDER BY data DESC LIMIT 5");
		if(!$readArtUtl){
			echo '<td colspan="2">Não existem artigos registrados</td>';
		}else{
			foreach($readArtUtl as $utl):
				echo '<tr>';
				echo '<td><a href="'.BASE.'/artigo/'.$utl['url'].'" target="_blank" title="ver">'.lmWord($utl['titulo'],22).'</a></td>';
				echo '<td align="center">'.date('d/m/y H:i',strtotime($utl['data'])).'</td>';
				echo '</tr>';
			endforeach;
		}
	  ?>
	</table>
	
	<table width="270" border="0" class="tbdados" style="float:right;" cellspacing="0" cellpadding="0">
	  <tr class="ses">
		<td>Artigos + vistos</td>
		<td align="center">Visitas</td>
	  </tr>
	  <?php 
		$readArtUtl = read('up_posts',"WHERE tipo = 'post' ORDER BY visitas DESC, data DESC LIMIT 5");
		if(!$readArtUtl){
			echo '<td colspan="2">Não existem artigos registrados</td>';
		}else{
			foreach($readArtUtl as $utl):
				$views = ($utl['visitas'] != '' ? $utl['visitas'] : '0');
				echo '<tr>';
				echo '<td><a href="'.BASE.'/artigo/'.$utl['url'].'" target="_blank" title="ver">'.lmWord($utl['titulo'],22).'</a></td>';
				echo '<td align="center">'.$views.'</td>';
				echo '</tr>';
			endforeach;
		}
	  ?>
	</table>
</div><!-- /bloco home -->