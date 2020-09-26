<?php 
	$catUrl = mysql_real_escape_string($url[1]);
	$readCat = read('up_cats',"WHERE url = '$catUrl'");
	if(!$readCat){
		header('Location: '.BASE.'/404');
	}else
		foreach($readCat as $cat);
?>
<title><?php echo SITENAME.' | '.$cat['nome']; ?></title>
<meta name="title" content="<?php echo SITENAME.' | '.$cat['nome']; ?>" />
<meta name="description" content="<?php echo $cat['content']; ?>" />
<meta name="keywords" content="<?php echo $cat['tags']; ?>" />
<meta name="author" content="<?php echo SITEAUTOR; ?>" />   
<meta name="url" content="<?php echo BASE.'/categoria/'.$cat['url']; ?>" />  
<meta name="language" content="pt-br" /> 
<meta name="robots" content="INDEX,FOLLOW" /> 
</head>
<body>

<div id="site">

<?php setArq('tpl/header'); ?>

<div id="content">

<div class="categoria">
    	<h1><?php echo ($cat['id_pai'] != '' ? ucfirst (strtolower(getCat($cat['id_pai'], 'nome'))).' -> '.ucfirst(strtolower($cat['nome'])) : ucfirst(strtolower($cat['nome']))); ?></h1>
			<?php 
				echo '<ul class="arts">';
				$pag = (empty($url[3])? '1' : $url[3]);
				$maximo = 8;
				$inicio = ($pag * $maximo) - $maximo;
				if($cat['id_pai'] != ''){
					$readArtigos = read('up_posts',"WHERE categoria = '$cat[id]' AND tipo = 'post' AND status = '1' ORDER BY data DESC LIMIT $inicio, $maximo");
				}else{
					$readArtigos = read('up_posts',"WHERE cat_pai = '$cat[id]' AND tipo = 'post' AND status = '1' ORDER BY data DESC LIMIT $inicio, $maximo");					
				}
				foreach($readArtigos as $art):
					$catcon++;
					echo '<li';
					if($catcon%4==0)echo ' class="last"';
					echo '>';
						getThumb($art['thumb'], $art['tags'], $art['titulo'], '200', '150', '', '', BASE.'/artigo/'.$art['url'], 't');
						echo '<p class="data">'.date('d/m/Y H:i',strtotime($art['data'])).'</p>';
						echo '<p class="titulo"><a title="Ver mais de '.$art['titulo'].'" href="'.BASE.'/artigo/'.$art['url'].'" class="link">'.lmWord($art['titulo'],50).'</a></p>';
					echo '</li>';
				endforeach;
				echo '</ul>';
				$link = BASE.'/categoria/'.$cat['url'].'/page/';
				if($cat['id_pai'] != ''){
					readPaginator('up_posts', "WHERE categoria = '$cat[id]' AND tipo = 'post' AND status = '1' ORDER BY data DESC", $maximo, $link, $pag, '860px');
				}else{
					readPaginator('up_posts', "WHERE cat_pai = '$cat[id]' AND tipo = 'post' AND status = '1' ORDER BY data DESC", $maximo, $link, $pag, '860px');
				}
			?>
          
</div><!-- /categoria -->
</div><!-- //content -->