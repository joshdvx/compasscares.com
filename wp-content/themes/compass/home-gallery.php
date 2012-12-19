<?php query_posts('post_type=photo_galleries&orderby=rand'); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php $images = get_field('select_photos'); if( $images ): ?>
	     <?php      
		    $i = 0;
			$display_count = min(count($images), 15);

			for($x = 0; $x < $display_count; $x++) {
				if( $i == 0 ) {
					echo '<div class="item">';
				}
				$image = $images[$x];
				echo '<img src="' . $image['sizes']['thumbnail'] . '" alt="' . $image['alt'] . '" />';
				if( $i == 2 || $x == $display_count - 1) {
					echo '</div>';
					$i = 0;
				}
				else {
					$i++;
				}
			}   
		?>                
	<?php endif; ?>
<?php endwhile; endif; ?>