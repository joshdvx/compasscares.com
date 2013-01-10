<?php get_header(); ?>

<section id="page" class="all-success-stories bg-all">
	<div class="heading">
		<div class="heading">
			<h1 class="section-header">
				<div class="main-title">We Are Amazing</div>
				<div class="subtitle">Success Stories</div>
			</h1>
			<div class="heading-arrow"></div>
		</div>
	</div><!-- .heading -->
	
	<ul class="nav nav-tabs gallery-filters">
		<li class="filters">Filters:</li>
		<li class="active all">
			<a href="<?php bloginfo('url') ?>/success-stories">ALL</a>
		</li>
		<li class="sls">
			<a href="<?php bloginfo('url') ?>/success-stories/sls">SLS</a>
		</li>
		<li class="ils">
			<a href="<?php bloginfo('url') ?>/success-stories/ils">ILS</a>
		</li>
	</ul><!-- .gallery-filters -->
	
	<div id="success-stories">
	<?php query_posts('post_type=success_stories&showposts=9'); ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<a href="<?php the_permalink(); ?>" class="ss-hover">
			<div class="ss-hover-img">
				<img src="<?php the_field('success_story_headshot') ?>" alt="<?php the_title(); ?>'s Story">
				<span class="<?php echo strip_tags (get_the_term_list( $post->ID, 'success_story_category' )); ?>">- Read <?php the_title(); ?>'s Story -</span>
			</div>
			<div class="content <?php echo strip_tags (get_the_term_list( $post->ID, 'success_story_category' )); ?>">
				<div class="inner"><span>- <?php the_title(); ?>'s -</span><br>Success<br>Story</div>
			</div>
		</a>
		<?php endwhile; endif; ?>
		<div class="clearfix"></div>
	</div>
	<div class="cta bg-all">
			
		<?php bootstrap_pagination(); ?>
	
		<div class="cta-links">
			<a href="<?php bloginfo('url') ?>/careers-opportunities">Wanna work at Compass?</a>
			<a href="<?php bloginfo('url') ?>/ils">Learn more about ILS</a>
			<a href="<?php bloginfo('url') ?>/sls">Learn more about SLS</a>
		</div>
	</div>
	
</section><!-- #page -->
<?php get_footer(); ?>
