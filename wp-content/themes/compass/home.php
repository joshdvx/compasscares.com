<?php get_header(); ?>

<section id="page">
	
	<div id="sliderFrame">
	    <div id="slider">	       	
	       	<?php if(get_field('hero_unit_banners','option')): ?>
				<?php while (has_sub_field('hero_unit_banners','option')): ?>
					<img src="<?php the_sub_field('hero_banner_image','option') ?>" alt="<?php the_sub_field('hero_caption','option') ?>">
		      	<?php endwhile; ?>
			<?php endif; ?>
	    </div>
	</div><!-- #sliderFrame -->
	
	<div id="services">
		<h2 class="section-header">We Do Amazing Things.</h2>
		<div class="row">
			<div class="span6">ILS</div>
			<div class="span6">SLS</div>
		</div>
	</div><!-- #services -->
	
	<div id="success-stories">
		<h2 class="section-header">We Are Amazing.</h2>
		<?php query_posts("post_type=success_stories&showposts=3"); ?>
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<h3><?php the_title(); ?></h3>
				<?php the_excerpt(); ?>
		<?php endwhile; endif; ?>
	</div><!-- #success-stories -->
	
	<div id="insta-team">
		<h2 class="section-header">This is Us.</h2>
		<div id="instagram" class="carousel slide" data-interval="false">
			<!-- Carousel items -->
			<div class="carousel-inner">
				<?php get_template_part('instagram'); ?>
			</div>
				<!-- Carousel nav -->
				<a class="carousel-control left" href="#instagram" data-slide="prev">&lsaquo;</a>
				<a class="carousel-control right" href="#instagram" data-slide="next">&rsaquo;</a>
		</div><!-- #carousel -->
	</div><!-- #insta-team -->
	
	<div id="events">
		<h2 class="section-header">Schedule This.</h2>
		<?php echo do_shortcode('[events_list limit="2"]
									<h3>#_EVENTNAME</h3>
									<p>#l, #F #j, #Y at #g:#i#a until #@_{l, F j, Y} in #_LOCATIONTOWN, #_LOCATIONSTATE</p>
								[/events_list]'); ?>
	</div><!-- #events -->
	<div id="latest-photos">
		<h2 class="section-header">We Look Good.</h2>
		<div id="gallery" class="carousel slide" data-interval="false">
			<div class="carousel-inner">
				<?php get_template_part('home-gallery'); ?>
			</div><!-- .carousel-inner -->
				<a class="carousel-control left" href="#gallery" data-slide="prev">&lsaquo;</a>
				<a class="carousel-control right" href="#gallery" data-slide="next">&rsaquo;</a>
		</div><!-- #gallery .carousel-->
	</div>
	<div id="latest-post">
		<h2 class="section-header">As We Were Saying</h2>
		<?php query_posts('showposts=1'); ?>
		<?php get_template_part( 'loop', 'home' ); ?>
	</div>
	
</section><!-- #page -->
<?php get_footer(); ?>
