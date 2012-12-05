<?php get_header(); ?>

<section id="page" class="span12">

	<iframe src="http://snapwidget.com/sl/?h=Y29tcGFzc2NhcmVzfGlufDEwMHwyfDN8fHllc3w1fG5vbmU=" allowTransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:115px; height: 115px" ></iframe>
	
	<h1>Success Stories</h1>
	<?php query_posts("post_type=success_stories&showposts=3"); ?>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<h2><?php the_title(); ?></h2>
	<?php the_content(); ?>
	<?php endwhile; ?>
	<!-- post navigation -->
	<?php else: ?>
	<!-- no posts found -->
	<?php endif; ?>
	
	<h1>From the Blog</h1>
	<?php query_posts('showposts=1'); ?>
	<?php get_template_part( 'loop', 'home' ); ?>

</section><!-- #page -->
<?php get_footer(); ?>
