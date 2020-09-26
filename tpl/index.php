<head>
<title><?php echo SITENAME.' - '.SITETAGS; ?></title>
<meta name="title" content="<?php echo SITENAME.' - '.SITETAGS; ?>" />
<meta name="description" content="<?php echo SITEDESC; ?>" />
<meta name="keywords" content="<?php echo SITETAGS; ?>" />
<meta name="author" content="<?php echo SITEAUTOR; ?>" />
<meta name="url" content="<?php echo BASE; ?>" />
<meta name="language" content="pt-br" /> 
<meta name="robots" content="INDEX,FOLLOW" /> 
</head>
<body>

<div id="site">

<?php setArq('tpl/header'); ?>

<div id="content">

<div class="home">
	<div class="slide">
    	<ul>
			<?php 
				$readSlide = read('(SELECT * FROM up_posts ORDER BY data DESC) up_posts', "WHERE tipo = 'post' AND status = '1' ORDER BY data DESC LIMIT 4");
				foreach($readSlide as $slide):
					echo '<li>';
						getThumb($slide['thumb'], $slide['tags'], $slide['titulo'], '866', '254', '', '', '#', 'c');
						echo '<div class="info">';
							echo '<p class="titulo"><a href="'.BASE.'/artigo/'.$slide['url'].'" title="Ver mais de '.$slide['titulo'].'">'.getCat($slide['cat_pai'],'nome').' - '.$slide['titulo'].'</a></p>';
							echo '<p class="resumo"><a href="'.BASE.'/artigo/'.$slide['url'].'" title="Ver mais de '.$slide['titulo'].'">'.lmWord($slide['content'],300).'</a></p>';
						echo '</div><!-- /info -->';
					echo '</li>';
				endforeach;
			?>
        </ul>
        <div class="slidenav"></div><!-- /slide nav-->
    </div><!-- /slide -->
    
	<?php 
		//Obter o id das categorias pai dos blocos a exibir.
		$readCatPai = read('up_cats', "WHERE id_pai IS NULL ORDER BY id ASC LIMIT 4");
		foreach($readCatPai as $catPai):
			$catCount++;

			if ($catCount == 1){
				echo '<ul class="blocoa">';					
					echo '<h2> '.$catPai['nome'].'</h2>';
			
					$readBloco = read('up_posts',"WHERE tipo = 'post' AND status = '1' AND cat_pai = '$catPai[id]' ORDER BY data DESC LIMIT 1,5");
					foreach($readBloco as $bl):
						$i++;
						echo '<li';
						if($i==4) echo ' class="last"';
						echo '>';
							getThumb($bl['thumb'], $bl['tags'], $bl['titulo'], '168', '234', '', '', '#');
							echo '<div class="info">';
								echo '<p class="data">'.date('d/m/Y H:i',strtotime($bl['data'])).'</p>';
								echo '<p class="titulo"><a href="'.BASE.'/artigo/'.$bl['url'].'" title="Ver mais de '.$bl['titulo'].'">'.$bl['titulo'].'</a></p>';
							echo '</div><!-- /info -->';
						echo '</li>';
					endforeach;
				echo '</ul><!-- /blocoa -->';			
			}
	
			if ($catCount == 2){
				echo '<div class="blocob">';
					echo '<div class="ladoa">';
						echo '<h2> '.$catPai['nome'].'</h2>';
						echo '<ul class="ulladoa">';
								$readBloco = read('up_posts',"WHERE tipo = 'post' AND status = '1' AND cat_pai = '$catPai[id]' ORDER BY data DESC LIMIT 1,3");
								foreach($readBloco as $bl):
									echo '<li class="gli">';
										getThumb($bl['thumb'], $bl['tags'], $bl['titulo'], '180', '100', '', '', '#');
										echo '<p class="titulo"><a href="'.BASE.'/artigo/'.$bl['url'].'" title="Ver mais de '.$bl['titulo'].'">'.$bl['titulo'].'</a></p>';
										echo '<p class="data">'.date('d/m/Y H:i',strtotime($bl['data'])).'</p>';
										echo '<span class="link"><a href="'.BASE.'/artigo/'.$bl['url'].'" title="Ver mais de '.$bl['titulo'].'" class="radius bsshadow">continue lendo...</a></span>';
									echo '</li>';
								endforeach;
						echo '</ul><!-- /ulladoa -->';
					echo '</div><!-- /ladoa -->';
				}
				if ($catCount == 3){
					echo '<div class="ladob">';
						echo '<h2> '.$catPai['nome'].'</h2>';
						echo '<ul class="ulladob">';
								$readBloco = read('up_posts',"WHERE tipo = 'post' AND status = '1' AND cat_pai = '$catPai[id]' ORDER BY data DESC LIMIT 1,3");
								foreach($readBloco as $bl):
									$t++;
									echo '<li class="bsshadow';
									if($t==2) echo ' last';
									echo '">';
										getThumb($bl['thumb'], $bl['tags'], $bl['titulo'], '133', '237', '', '', '#');
										echo '<span class="info">';
											echo '<p class="titulo"><a href="'.BASE.'/artigo/'.$bl['url'].'" title="Ver mais de '.$bl['titulo'].'">'.$bl['titulo'].'</a></p>';
											echo '<p class="data">'.date('d/m/Y H:i',strtotime($bl['data'])).'</p>';
										echo '</span>';	
									echo '</li>';
								endforeach;
						echo '</ul><!-- /ulladob -->';
						
						echo '<ul class="ultecover">';
								$readBloco = read('up_posts',"WHERE tipo = 'post' AND status = '1' AND cat_pai = '$catPai[id]' ORDER BY data DESC LIMIT 4,2");
								foreach($readBloco as $bl):
									$al++;
									$class = ($al == '1' ? 'tp' : 'bt');
									echo '<li class="'.$class.' bsshadow">';
										echo '<span class="data">'.date('d/m',strtotime($bl['data'])).'</span>';
										echo '<p class="titulo"><a href="'.BASE.'/artigo/'.$bl['url'].'" title="Ver mais de '.$bl['titulo'].'">'.$bl['titulo'].'</a></p>';
									echo '</li>';
								endforeach;
						echo '</ul>';
					echo '</div><!-- /ladob -->';
				echo '</div><!-- /blocob -->';			
			}

			if ($catCount == 4){
				echo '<div class="blococ">';					
					echo '<h2> '.$catPai['nome'].'</h2>';
					echo '<ul class="inter">';
							$readBloco = read('up_posts',"WHERE tipo = 'post' AND status = '1' AND cat_pai = '$catPai[id]' ORDER BY data DESC LIMIT 1,4");
							foreach($readBloco as $bl):
								$n++;
								echo '<li class="bsshadow radius';
								if($n==3) echo ' last';
								echo '">';
									getThumb($bl['thumb'], $bl['tags'], $bl['titulo'], '200', '150', '', '', BASE.'/artigo/'.$bl['url']);
									echo '<p class="data">'.date('d/m/Y H:i',strtotime($bl['data'])).'</p>';
									echo '<p class="titulo">'.$bl['titulo'].'</p>';
								echo '</li>';
							endforeach;
					echo '</ul>';
				echo '</div>';
			}
			
		endforeach;
	?>
	
</div><!-- /home -->
</div><!-- //content -->