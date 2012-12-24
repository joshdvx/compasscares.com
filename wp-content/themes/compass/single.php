<?php get_header(); ?>

		<section id="page">
			<div class="blog-posts single-post">

				<?php get_template_part( 'loop', 'single' ); ?>
			</div>
			<?php get_sidebar(); ?>
		</section><!-- #page -->


<?php get_footer(); ?>
