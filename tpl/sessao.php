<?php 
	$artigoUrl  = mysql_real_escape_string($url[1]);
	$readArtigo = read('up_posts',"WHERE url = '$artigoUrl'");
	if(!$readArtigo){
		header('Location: '.BASE.'/404');
	}else
		foreach($readArtigo as $art);
		setViews($art['id']);
?>

<title><?php echo $art['titulo'].' | '.SITENAME; ?></title>
<meta name="title" content="<?php echo SITENAME; ?>" />
<meta name="description" content="<?php echo lmWord($art['content'],100); ?>" />
<meta name="keywords" content="<?php echo $art['tags']; ?>" />
<meta name="author" content="<?php echo SITEAUTOR; ?>" />   
<meta name="url" content="<?php echo BASE.'/artigo/'.$art['url']; ?>" />
<meta name="language" content="pt-br" /> 
<meta name="robots" content="INDEX,FOLLOW" /> 
</head>
<body>

<div id="site">

<?php setArq('tpl/header'); ?>

<div id="content">

<div class="single">
	<h1 class="pgtitulo"><?php echo $art['titulo']; ?></h1>
      
    <div class="content">
		<?php echo $art['content']; ?>
      
			<?php
				$readArtGb = read('up_posts_gb',"WHERE post_id = '$art[id]'");
				if($readArtGb){
					echo '<ul class="gallery">';
						foreach($readArtGb as $gb):
							$gbnum++;
							echo '<li';
							if($gbnum%5==0) echo ' class="last" ';
							echo '>';
							getThumb($gb['img'], $art['titulo'].' ( imagem '.$gbnum.')', $art['titulo'], '100', '65', $art['id'], '', '', 't');
							echo '</li>';
						endforeach;
					echo '</ul><!-- //gallery -->';
				} 
			?>
    
    </div><!-- // content -->
    
    <div class="sidebar">
    	<?php setArq('tpl/sidebar'); ?>
    </div><!-- //sidebar -->
   </div><!-- /single -->
</div><!-- //content -->