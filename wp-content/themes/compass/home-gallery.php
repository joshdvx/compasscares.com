
<?php $all_images = array(); ?>

<?php query_posts('post_type=photo_galleries'); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php $images = get_field('select_photos'); if( $images ): ?>
	     <?php      
			foreach( $images as $image ):
				array_push($all_images, '<a href=" ' . $image['sizes']['large'] . '" rel="lightbox"><img src="' . $image['sizes']['home_photos'] . '" alt="' . $image['alt'] . '" /></a>');
			endforeach;     
		?>                
	<?php endif; ?>
<?php endwhile; endif; ?>

<?php 
    $i = 0;
    $x = 0;		

	$display_count = min(count($all_images), 15);

	foreach( $all_images as $image ):
		$x++;
		if( $i == 0 ) {
			echo '<section class="item">';
		}

		echo $image;

		if( $x == $display_count) {
			echo '</section>';
			break;
		}
		else if( $i == 2) {
			echo '</section>';
			$i = 0;
		} else {
			$i++;
		}
	endforeach; 

?>

