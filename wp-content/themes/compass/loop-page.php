<?php
/**
 * The loop that displays a page.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-page.php.
 *
 */
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<!--<?php if(get_field('subtitle')): ?>
			<h1><?php the_field('subtitle') ?></h1>
		<?php else: ?>
			<h1><?php the_title(); ?></h1>
		<?php endif; ?>-->
		
		<div class="heading">
			<h2 class="section-header"><?php the_title(); ?><span class="subtitle"><?php the_field('subtitle') ?></span></h2>
			<div class="heading-arrow"></div>
		</div>		

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'smm' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-## -->

<?php endwhile; // end of the loop. ?>