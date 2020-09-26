<?php
	$search = mysql_real_escape_string($url[1]);
	$search = str_replace('-',' ',$search );
?>
<title>Pesquisa por: <?php echo $search.' | '.SITENAME; ?></title>
<meta name="title" content="Pesquisa por: <?php echo $search.' | '.SITENAME; ?>" />
<meta name="description" content="<?php echo SITENAME; ?> | Pesquisa realacionada à <?php echo $search; ?>" />
<meta name="keywords" content="<?php echo str_replace(' ',', ',$search); ?>" />
<meta name="author" content="<?php echo SITEAUTOR; ?>" />   
<meta name="url" content="<?php echo BASE.'/pesquisa/'.str_replace(' ','-',$search); ?>" />  
<meta name="language" content="pt-br" /> 
<meta name="robots" content="INDEX,FOLLOW" /> 
</head>
<body>

<div id="site">

<?php setArq('tpl/header'); ?>

<div id="content">

<div class="single">
	<h1 class="pgtitulo">Pesquisa por: <?php echo strtoupper($search); ?></h1>
    <div class="content">
		<?php
			$readPesquisa = read('up_posts',"WHERE tipo = 'post' AND status = '1' AND (titulo LIKE '%$search%' OR content LIKE '%$search%' OR tags LIKE '%$search%') ORDER BY data DESC");
			echo '<p style="margin:0;">Sua pesquisa retornou <strong>';
			echo count($readPesquisa);
			echo '</strong> resultados!</p>';
			if(count($readPesquisa) <= 0){
				echo '<br /><h2>Desculpe, sua pesquisa não retornou resultados.</h2>';
				echo '<p>Você pode tentar outros termos, ou navegar em nosso menu! Talvez você queira resumir sua pesquisa. Pesquisa com muitas palavras as vezes nã retornam resultados!</p>';
			}else{
				echo '<ul class="searchlist">';
					$pag = (empty($url[3])? '1' : $url[3]);
					$maximo = 7;
					$inicio = ($pag * $maximo) - $maximo;
					$readSearch = read('up_posts',"WHERE tipo = 'post' AND status = '1' AND (titulo LIKE '%$search%' OR content LIKE '%$search%' OR tags LIKE '%$search%') ORDER BY data DESC LIMIT $inicio, $maximo");
					foreach($readSearch as $pesquisa):
						echo '<li>';
							getThumb($pesquisa['thumb'], $pesquisa['tags'], $pesquisa['titulo'], '180', '100', '', '', '#', 't');
							echo '<a href="'.BASE.'/artigo/'.$pesquisa['url'].'" title="Ver mais de '.$pesquisa['titulo'].'">'.$pesquisa['titulo'].'</a>';
						echo '</li>';
					endforeach;
				echo '</ul><!-- /searchlist -->';
				$link = BASE.'/pesquisa/'.str_replace(' ','-',$search).'/page/';
				readPaginator('up_posts', "WHERE tipo = 'post' AND status = '1' AND (titulo LIKE '%$search%' OR content LIKE '%$search%' OR tags LIKE '%$search%') ORDER BY data DESC", $maximo, $link, $pag, '540px');
			}
		?>
    </div><!-- // content -->
    
    <div class="sidebar">
    	<?php setArq('tpl/sidebar'); ?>
    </div><!-- //sidebar -->
   </div><!-- /single -->
</div><!-- //content -->