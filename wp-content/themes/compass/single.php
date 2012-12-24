<?php get_header(); ?>

		<section id="page" class="row">
			<div class="blog-posts single-post span10">

				<?php get_template_part( 'loop', 'single' ); ?>
			</div>
			<?php get_sidebar(); ?>
		</section><!-- #page -->


<?php get_footer(); ?>
