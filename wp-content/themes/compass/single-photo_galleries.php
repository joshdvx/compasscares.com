<?php get_header(); ?>

<section id="page" class="span12">
	<div class="row">		
		
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
				<?php endif; 
			?>
		</div>
		<?php endwhile; ?>
		<!-- post navigation -->
		<?php else: ?>
		<!-- no posts found -->
		<?php endif; ?>
	</div>

</section><!-- #page -->
<?php get_footer(); ?>
