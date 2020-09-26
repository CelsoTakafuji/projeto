<title>404 | Opssss não encontrado! |<?php echo SITENAME; ?></title>
<meta name="title" content="404 | <?php echo SITENAME.' - '.SITETAGS; ?>" />
<meta name="description" content="<?php echo SITEDESC; ?>" />
<meta name="keywords" content="<?php echo SITETAGS; ?>" />
<meta name="author" content="<?php echo SITEAUTOR; ?>" />
<meta name="url" content="<?php echo BASE.'/404'; ?>" />
<meta name="language" content="pt-br" /> 
<meta name="robots" content="INDEX,FOLLOW" /> 
</head>
<body>

<div id="site">

<?php setArq('tpl/header'); ?>

<div id="content">

	<div class="notfound">
    
    	<h1 class="pgtitulo">Opppppssss, não conseguimos encontrar o que você procura!</h1>
        <p class="pgtext">A url que você informou não retornou nem um resultado. Talvez a página tenha sido removida ou o artigo não 
        existe mais. Você pode tentar navegar pelo nosso menu ou pesquisar pelo sistema Ou ainda voltar para a
        <a class="pglink" href="<?php setHome();?>" title="Voltar ao início | <?php echo SITENAME; ?>">Home</a>.</p>
        <p class="pgtext"><strong>Você pode conferir nossas últimas atualizações. Elas estão logo abaixo!</strong></p>
    
    
    <div class="bloco entre">
    	<h2><?php echo getCat(1, 'nome'); ?></h2>
        <ul class="inter">
        	<?php 
				$readBloco = read('up_posts',"WHERE tipo = 'post' AND status = '1' AND cat_pai = '1' ORDER BY data DESC LIMIT 4");
				foreach($readBloco as $bl):
					$ba++;
					echo '<li class="bsshadow radius';
					if($ba==3) echo ' last';
					echo '">';
						getThumb($bl['thumb'], $bl['tags'], $bl['titulo'], '200', '150', '', '', BASE.'/artigo/'.$bl['url'], 't');
						echo '<p class="data">'.date('d/m/Y H:i',strtotime($bl['data'])).'</p>';
						echo '<p class="titulo">'.lmWord($bl['titulo'],50).'</p>';
					echo '</li>';
				endforeach;
			?>
        </ul>
    </div>
    
    <div class="bloco games">
    	<h2><?php echo getCat(2, 'nome'); ?></h2>
        <ul class="inter">
        	<?php 
				$readBloco = read('up_posts',"WHERE tipo = 'post' AND status = '1' AND cat_pai = '2' ORDER BY data DESC LIMIT 4");
				foreach($readBloco as $bl):
					$bb++;
					echo '<li class="bsshadow radius';
					if($bb==3) echo ' last';
					echo '">';
						getThumb($bl['thumb'], $bl['tags'], $bl['titulo'], '200', '150', '', '', BASE.'/artigo/'.$bl['url'], 't');
						echo '<p class="data">'.date('d/m/Y H:i',strtotime($bl['data'])).'</p>';
						echo '<p class="titulo">'.lmWord($bl['titulo'],50).'</p>';
					echo '</li>';
				endforeach;
            ?>
        </ul>
    </div>
    
    <div class="bloco tecno">
    	<h2><?php echo getCat(3, 'nome'); ?></h2>
        <ul class="inter">
        	<?php 
				$readBloco = read('up_posts',"WHERE tipo = 'post' AND status = '1' AND cat_pai = '3' ORDER BY data DESC LIMIT 4");
				foreach($readBloco as $bl):
					$bc++;
					echo '<li class="bsshadow radius';
					if($bc==3) echo ' last';
					echo '">';
						getThumb($bl['thumb'], $bl['tags'], $bl['titulo'], '200', '150', '', '', BASE.'/artigo/'.$bl['url'], 't');
						echo '<p class="data">'.date('d/m/Y H:i',strtotime($bl['data'])).'</p>';
						echo '<p class="titulo">'.lmWord($bl['titulo'],50).'</p>';
					echo '</li>';
				endforeach;
            ?>
        </ul>
    </div>
    
    <div class="bloco">
    	<h2><?php echo getCat(4, 'nome'); ?></h2>
        <ul class="inter">
        	<?php 
				$readBloco = read('up_posts',"WHERE tipo = 'post' AND status = '1' AND cat_pai = '2' ORDER BY data DESC LIMIT 4");
				foreach($readBloco as $bl):
					$bd++;
					echo '<li class="bsshadow radius';
					if($bd==3) echo ' last';
					echo '">';
						getThumb($bl['thumb'], $bl['tags'], $bl['titulo'], '200', '150', '', '', BASE.'/artigo/'.$bl['url'], 't');
						echo '<p class="data">'.date('d/m/Y H:i',strtotime($bl['data'])).'</p>';
						echo '<p class="titulo">'.lmWord($bl['titulo'],50).'</p>';
					echo '</li>';
				endforeach;
            ?>
        </ul>
    </div>
    
    </div><!-- //notfound -->

</div><!-- //content -->