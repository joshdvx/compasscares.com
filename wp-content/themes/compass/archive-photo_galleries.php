<?php get_header(); ?>

<section id="page" class="span12">
	<div class="row">		
		<p><?php echo get_the_term_list( $post->ID, 'regions', '', ', ', '' ); ?></p>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div class="span4">
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php
				$images = get_field('select_photos');
			 
				if( $images ): ?>
				    <div id="carousel" class="flexslider">
				        <ul class="slides">
				            <?php foreach( $images as $image ): ?>
				                <li>
				                    <img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
				                    
				                </li>
				                
				            <?php endforeach; ?>
				        </ul>
				    </div>
				    <p><?php echo get_the_term_list( $post->ID, 'regions', '', ', ', '' ); ?></p>
				    <?php echo count($images); ?>
				<?php endif; ?>
		</div>
		
		<?php endwhile; endif; ?>
	</div>

</section><!-- #page -->
<?php get_footer(); ?>
