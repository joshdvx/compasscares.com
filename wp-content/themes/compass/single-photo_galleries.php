<?php get_header(); ?>

<section id="page" class="span12">
	<div class="row">		
		
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php
				$images = get_field('select_photos');
				if( $images ): ?>
		
			            <?php foreach( $images as $image ): ?>
			            	<div class="span4">
			                	<a href="<?php echo $image['sizes']['large']; ?>" rel="lightbox"><img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['title']; ?>" /></a>
			                	<p><?php echo $image['caption']; ?></p>
			            	</div>
			            <?php endforeach; ?>

			<?php endif; ?>
		
		<?php endwhile; endif; ?>
	</div>

</section><!-- #page -->
<?php get_footer(); ?>
