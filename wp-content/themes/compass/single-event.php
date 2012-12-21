<?php get_header(); ?>

		<section id="page">

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php the_title(); ?>
			<?php endwhile; ?>
			<!-- post navigation -->
			<?php else: ?>
			<!-- no posts found -->
			<?php endif; ?>

		</section><!-- #page -->


<?php get_footer(); ?>
