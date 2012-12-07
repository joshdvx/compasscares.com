<?php get_header(); ?>

<section id="page" class="span12">
<div class="scrollblock" id="hero">
	<div id="sliderFrame">
	    <div id="slider">	       	
	       	<?php if(get_field('hero_unit_banners','option')): ?>
				<?php while (has_sub_field('hero_unit_banners','option')): ?>
					<img src="<?php the_sub_field('hero_banner_image','option') ?>" alt="<?php the_sub_field('hero_caption','option') ?>">
		      	<?php endwhile; ?>
			<?php endif; ?>
	    </div>
	</div><!-- #sliderFrame -->
</div>
	<div class="scrollblock" id="services">
		<h2>We Do Amazing Things</h2>
		<div id="unpin"></div>
	</div><!-- .scrollblock -->
	
	<div class="scrollblock" id="success-stories">
		<h2>We Are Amazing.</h2>
		<?php query_posts("post_type=success_stories&showposts=3"); ?>
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<h3><?php the_title(); ?></h3>
				<?php the_excerpt(); ?>
		<?php endwhile; endif; ?>
		<div id="unpin2"></div>
	</div>
	
	<div class="scrollblock" id="team">
		<h2>This is Us.</h2>
		
		<div id="instagram" class="carousel slide" data-interval="false">
			<!-- Carousel items -->
			<div class="carousel-inner">
				<?php get_template_part('instagram'); ?>
			</div>
				<!-- Carousel nav -->
				<a class="carousel-control left" href="#instagram" data-slide="prev">&lsaquo;</a>
				<a class="carousel-control right" href="#instagram" data-slide="next">&rsaquo;</a>
		</div><!-- #carousel -->
		<div id="unpin3"></div>
	</div>
	
	<div class="scrollblock" id="events">
		<h3>Upcoming events</h3>
		<?php echo do_shortcode('[events_list limit="1"]
									<h3>#_EVENTNAME</h3>
									<p>#l, #F #j, #Y at #g:#i#a until #@_{l, F j, Y} in #_LOCATIONTOWN, #_LOCATIONSTATE</p>
								[/events_list]'); ?>
		<div id="unpin4"></div>
	</div>
	
	<div class="scrollblock" id="blog">
		<h2>From the Blog</h2>
		<?php query_posts('showposts=1'); ?>
		<?php get_template_part( 'loop', 'home' ); ?>
		<div id="unpin5"></div>
	</div>
	
</section><!-- #page -->
<?php get_footer(); ?>
