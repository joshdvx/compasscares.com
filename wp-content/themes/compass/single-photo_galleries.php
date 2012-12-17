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
			                	<img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
			            	</div>
			            <?php endforeach; ?>

			<?php endif; ?>
		
		<?php endwhile; endif; ?>
	</div>

</section><!-- #page -->
<?php get_footer(); ?>
