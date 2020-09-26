<ul class="box">
	<li>
    	<h3>Top 5</h3>
        <ul class="top">
        	<?php
				$readTop = read('up_posts',"WHERE tipo = 'post' AND status = '1' ORDER BY visitas DESC LIMIT 5");
				foreach($readTop as $top):
					$topNum++;
					echo '<li>';
						echo '<a href="'.BASE.'/artigo/'.$top['url'].'" title="Ver mais sobre '.$top['titulo'].'">';
							echo '<span class="num">'.$topNum.'</span>';
							echo $top['titulo'];
						echo '</a>';
					echo '</li>';
				endforeach;
			?>
        </ul>
    </li>

	<li>
    	<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2F<?php echo PAGEFACE; ?>&amp;width=300&amp;height=585&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=true&amp;appId=209499939111093" 
		scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:585px;" allowTransparency="true"></iframe>
    </li>
</ul>