<?php get_header(); ?>

		<section id="page" class="single-event bg-blueDark">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<div class="heading">
					<h1 class="section-header">
						<div class="main-title"><?php echo get_the_title(28); ?></div>
						<div class="subtitle"><?php the_field('subtitle', '28') ?></div>
					</h1>
					<div class="heading-arrow"></div>
				</div>
			<?php endwhile; endif; ?>
			
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
				<?php endwhile; endif; ?>
			

		</section><!-- #page -->

<?php get_footer(); ?>
