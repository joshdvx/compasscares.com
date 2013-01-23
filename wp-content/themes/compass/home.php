<?php get_header(); ?>

<section id="page" class="homepage">
	
	<article id="sliderFrame">
	    <div id="slider">	       	
	       	<?php if(get_field('hero_unit_banners','option')): ?>
				<?php while (has_sub_field('hero_unit_banners','option')): ?>
					<img src="<?php the_sub_field('hero_banner_image','option') ?>" alt="<?php the_sub_field('hero_caption','option') ?>">
		      	<?php endwhile; ?>
			<?php endif; ?>
	    </div>
	</article><!-- #sliderFrame -->

	<div class="home-section">
	
		<div class="heading">
			<h2 class="section-header">We Do Amazing <span class="bold">Things</span><span class="subtitle">Our Services</span></h2>
			<div class="heading-arrow"></div>
		</div>
		
		<article id="services">
			<div id="sls-ils">
				<div id="sls">
					<span class="service-label">SLS</span>
					<span class="service-text">Supported Living</span>
					<span class="service-back">Back</span>
				</div>
				<div id="ils">
					<span class="service-label">ILS</span>
					<span class="service-text">Independant Living</span>
					<span class="service-back">Back</span>
				</div>
			</div>
			<div class="service-copy service-sls">
				<?php the_field('hp_sls', 'option'); ?>
			</div>
			<div class="service-copy service-ils">
				<?php the_field('hp_ils', 'option'); ?>
			</div>
		</article><!-- #services -->

	</div>
	
	<div class="home-section">

		<div class="heading">
			<h2 class="section-header">We Are <span class="bold">Amazing</span><span class="subtitle">Success Stories</span></h2>
			<div class="heading-arrow"></div>
		</div>
		
		<article id="success-stories">
			<a href="<?php bloginfo('url'); ?>/success-stories/sls" class="ss-hover">
				<div class="ss-hover-img">
					<img src="<?php the_field('home_sls_photo', 'option') ?>" alt="SLS">
					<span class="sls">- Read SLS Stories -</span>
				</div>
				<div class="content sls">
					<div class="inner"><span>- SLS -</span><br>Success<br>Stories</div>
				</div>
			</a>
			
			<a href="<?php bloginfo('url'); ?>/success-stories/ils" class="ss-hover">
				<div class="ss-hover-img">
					<img src="<?php the_field('home_ils_photo', 'option') ?>" alt="ILS">
					<span class="ils">- Read ILS Stories -</span>
				</div>
				<div class="content ils">
					<div class="inner"><span>- ILS -</span><br>Success<br>Stories</div>
				</div>
			</a>
			
			<a href="<?php bloginfo('url'); ?>/success-stories" class="ss-hover">
				<div class="ss-hover-img">
					<img src="<?php the_field('home_all_stories_photo', 'option') ?>" alt="SLS and ILS">
					<span class="sls">- Read ALL Stories -</span>
				</div>
				<div class="content all">
					<div class="inner"><span>- All -</span><br>Success<br>Stories</div>
				</div>
			</a>
			
		</article><!-- #success-stories -->

	</div>

	<div class="home-section">
	
		<div class="heading">
			<h2 class="section-header">This is <span class="bold">Us</span><span class="subtitle">Our Team Having Fun</span></h2>
			<div class="heading-arrow"></div>
		</div>
		
		<article id="insta-team">
			<div class="container">
				<div class="row">
					<div id="instagram" class="carousel slide" data-interval="false">
						<!-- Carousel items -->
						<div class="carousel-inner">
							<?php get_template_part('instagram'); ?>
						</div>
							<!-- Carousel nav -->
							<a class="carousel-control left" href="#instagram" data-slide="prev">&lsaquo;</a>
							<a class="carousel-control right" href="#instagram" data-slide="next">&rsaquo;</a>
					</div><!-- #carousel -->
				</div>
			</div><!-- .container -->
		</article><!-- #insta-team -->

	</div>

	<div class="home-section">
	
		<div class="heading">
			<h2 class="section-header">Schedule <span class="bold">This</span><span class="subtitle">Upcoming Events</span></h2>
			<div class="heading-arrow"></div>
		</div>
		
		<article id="events" class="bg-blueDark">
			<?php echo do_shortcode('[events_list limit="2"]
				<div class="info-block">
					<div class="info-block-details event-details">
						<h3 class="event-title">#_EVENTNAME</h3>
						<span class="event-date">#_EVENTDATES</span>
						<p class="event-description">#_EVENTEXCERPT</p>
						<a href="#_EVENTURL">More Info...</a>
					</div>
					#_EVENTIMAGE{590,255}
				</div>
			[/events_list]'); ?>
		</article><!-- #events -->

	</div>

	<div class="home-section">
	
		<div class="heading">
			<h2 class="section-header">We Look <span class="bold">Good</span><span class="subtitle">Latest Photos</span></h2>
			<div class="heading-arrow"></div>
		</div>
		
		<article id="latest-photos">
		<div class="container">
				<div class="row">
			<div id="gallery" class="carousel slide" data-interval="false">
				<div class="carousel-inner">
					<?php get_template_part('home-gallery'); ?>
				</div><!-- .carousel-inner -->
					<a class="carousel-control left" href="#gallery" data-slide="prev">&lsaquo;</a>
					<a class="carousel-control right" href="#gallery" data-slide="next">&rsaquo;</a>
			</div><!-- #gallery .carousel-->
			</div>
			</div>
		</article>

	</div>
	
	<div class="home-section">

		<div class="heading">
			<h2 class="section-header">As We Were <span class="bold">Saying...</span><span class="subtitle">Latest From the Blog</span></h2>
			<div class="heading-arrow"></div>
		</div>
		
		<article id="latest-post">
			<?php query_posts('showposts=1'); ?>
			<?php get_template_part( 'loop', 'home' ); ?>
		</article>

	</div>
	
</section><!-- #page -->
<?php get_footer(); ?>

