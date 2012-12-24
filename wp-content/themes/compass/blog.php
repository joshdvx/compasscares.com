<?php 
/*
	Template Name: Blog
*/
get_header(); ?>

<section id="page">
	<div class="blog-posts">
		<?php get_template_part( 'loop', 'blog' ); ?>	
		
	</div>
	<?php get_sidebar(); ?>
</section><!-- #page -->


<?php get_footer(); ?>
