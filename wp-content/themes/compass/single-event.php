<?php get_header(); ?>

		<section id="page" class="single-event bg-blueDark">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<div class="heading">
					<h1 class="section-header"><?php echo get_the_title(28); ?><span class="subtitle"><?php the_field('subtitle', '28') ?></span></h1>
					<div class="heading-arrow"></div>  
				</div>
			<?php endwhile; endif; ?>
			
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
				<?php endwhile; endif; ?>
			

		</section><!-- #page -->

<?php get_footer(); ?>
