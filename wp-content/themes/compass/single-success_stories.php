<?php get_header(); ?>

<section id="page" class="single-success-story bg-<?php echo strip_tags (get_the_term_list( $post->ID, 'success_story_category' )); ?>">
	
	<div class="heading">
		<div class="heading">
			<h1 class="section-header">
				<div class="main-title">We Are Amazing</div>
				<div class="subtitle">Success Stories</div>
			</h1>
			<div class="heading-arrow"></div>
		</div>
	</div><!-- .heading -->
	
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div class="story-unit">
		<img src="<?php the_field('single_story_hero') ?>" alt="<?php the_title(); ?>'s Story">
		<div id="left-grad"></div>
		<div id="right-grad"></div>
	</div>	
	
	<div class="story">
		<h2><strong><?php the_title(); ?>'s </strong> Story</h2>
		
		<?php the_content(); ?>
	</div><!-- .story -->	
		<?php endwhile; endif; ?>
	
	
	<div class="cta bg-<?php echo strip_tags (get_the_term_list( $post->ID, 'success_story_category' )); ?>">
		<div class="pagi-navi">
			<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&#x25B8;', 'Previous post link', 'twentyten' ) . '</span> %title&apos;s Story' ); ?>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</div>
			<div class="back-to-all"><a href="<?php bloginfo('url'); ?>/success-stories">back to all</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;</div>
			<div class="nav-next tar"><?php next_post_link( '%link', '%title&apos;s Story <span class="meta-nav">' . _x( '&#x25B8;', 'Next post link', 'twentyten' ) . '</span>' ); ?></div>
		</div>
		
		<div class="cta-links">
			<a href="<?php bloginfo('url') ?>/careers-opportunities">Wanna work at Compass?</a>
			<a href="<?php bloginfo('url') ?>/ils">Learn more about ILS</a>
			<a href="<?php bloginfo('url') ?>/sls">Learn more about SLS</a>
		</div>
	</div>
</section><!-- #page -->

<?php get_footer(); ?>
