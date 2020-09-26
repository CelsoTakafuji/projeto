<div id="header">

    <div class="logo">
        <a href="<?php setHome();?>" title="<?php echo SITENAME; ?> - Filmes, Games, Animes e HQ's">
            <img src="<?php setHome();?>/tpl/images/logo.png" alt="Logo <?php echo SITENAME; ?>" title="<?php echo SITENAME; ?> - Filmes, Games, Animes e HQ's" />
        </a>
    </div><!-- /header-logo -->
    
    <div class="search">
    	<form name="search" action="" method="post">
			<?php
				if(isset($_POST['search'])){
					$pesquisa = mysql_real_escape_string($_POST['s']);
					$pesquisa = setUri($pesquisa);
					header('Location: '.BASE.'/pesquisa/'.$pesquisa);
				}
			?>
        	<label><input type="text" name="s" value="" /></label>
            <input type="submit" class="btn" value="" name="search" />
        </form>
    </div><!-- /header-search -->
    
    <ul class="hnav">
    	<li><a title="<?php echo SITENAME; ?> | Home" href="<?php setHome();?>">Home</a></li>
    	<?php
			$readCatMenu = read('up_cats',"WHERE id_pai IS NULL ORDER BY nome ASC");
			foreach($readCatMenu as $catMenu):
				echo '<li><a title="'.$catMenu[nome].' | '.SITENAME.'" href="'.BASE.'/categoria/'.$catMenu['url'].'">'.$catMenu['nome'].'</a>';
					$readSubCatMenu = read('up_cats',"WHERE id_pai = '$catMenu[id]' ORDER BY nome ASC");
					if($readSubCatMenu):
					echo '<ul class="sub">';
						foreach($readSubCatMenu as $catSubMenu):
							echo '<li><a title="'.$catSubMenu[nome].' | '.SITENAME.'" href="'.BASE.'/categoria/'.$catSubMenu['url'].'">'.$catSubMenu['nome'].'</a>';
						endforeach;
					echo '</ul>';
					endif;
				echo '</li>';
			endforeach;
		?>
		<?php if(empty($_SESSION['autUser'])){
		?>
		<li><a title="<?php echo SITENAME; ?> | Cadastre-se" href="<?php setHome();?>/pagina/cadastro">Cadastre-se</a></li>
        <li><a title="<?php echo SITENAME; ?> | Logar-se" href="<?php setHome();?>/pagina/login">Logar-se</a></li>
        <?php } else { ?>
		<li><a title="<?php echo SITENAME; ?> | Meu Perfil" href="<?php setHome();?>/pagina/perfil">Meu cadastro</a></li>
        <li><a title="<?php echo SITENAME; ?> | Deslogar" href="<?php setHome();?>/pagina/logoff">Sair</a></li>
		<?php } ?>
		<li class="last"><a title="<?php echo SITENAME; ?> | Logar-se" href="<?php setHome();?>/pagina/contato">Contato</a></li>
    </ul><!-- /hnav -->
    
</div><!-- /header -->