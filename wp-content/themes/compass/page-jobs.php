<?php get_header(); ?>

	<section id="page">

		<div class="heading">
			<h1 class="section-header">
				<div class="main-title"><?php echo get_the_title(); ?></div>
				<div class="subtitle"><?php the_field('subtitle') ?></div>
			</h1>
			<div class="heading-arrow"></div>
		</div>
		
		<div class="company-jobs">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
			<?php endwhile; endif; ?>
		</div><!-- .company-jobs -->
		
	</section><!-- #page -->

<?php get_footer(); ?>

