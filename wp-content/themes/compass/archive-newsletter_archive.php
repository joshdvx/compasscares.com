<?php get_header(); ?>

<section id="page" class="span12">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<h1><?php the_title(); ?></h1>
	<a href="<?php the_field('newsletter_upload') ?>">Download</a>
	
	<?php endwhile; ?>
	<!-- post navigation -->
	<?php else: ?>
	<!-- no posts found -->
	<?php endif; ?>

</section><!-- #page -->

<?php get_footer(); ?>
