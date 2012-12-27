<?php 
/*
	Template Name: Blog
*/
get_header(); ?>

<section id="page" class="row">
	<div class="blog-posts span10">
		<?php get_template_part( 'loop', 'blog' ); ?>	
	</div>
	<?php get_sidebar(); ?>
</section><!-- #page -->

<?php get_footer(); ?>
