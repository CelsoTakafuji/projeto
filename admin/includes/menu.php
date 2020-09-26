    	<ul class="nav">
        	<li class="tt"><span>Artigos</span>
            	<ul class="sub">
                	<li><a href="index2.php?exe=posts/posts-create" title="Criar artigo">Criar artigo</a></li>
                    <li><a href="index2.php?exe=posts/posts" title="Editar artigo">Editar artigos</a></li>
			<?php if($_SESSION['autUser']['nivel'] == '1'){ ?>
					<li><a href="index2.php?exe=posts/categorias" title="Categorias">Categorias</a></li>
                </ul>
            </li>
            <li class="tt"><span>Páginas</span>
            	<ul class="sub">
                	<li><a href="index2.php?exe=paginas/paginas-create" title="Criar Página">Criar Página</a></li>
                    <li><a href="index2.php?exe=paginas/paginas" title="Editar Páginas">Editar páginas</a></li>
                </ul>
            </li>
            <li class="tt"><span>Usuários</span>
            	<ul class="sub">
			<?php } ?>
                	<li><a href="index2.php?exe=usuarios/usuarios-edit&userid=<?php echo $_SESSION['autUser']['id']; ?>" title="Perfil">Meu perfil</a></li>
					<?php if($_SESSION['autUser']['nivel'] == '1'){ ?>
                    <li><a href="index2.php?exe=usuarios/usuarios-create" title="Criar Usuário">Criar usuário</a></li>
                    <li><a href="index2.php?exe=usuarios/usuarios" title="Gerenciar Usuário">Gerenciar usuários</a></li>
					<?php } ?>
					</ul>
            </li>
        </ul><!-- /nav -->