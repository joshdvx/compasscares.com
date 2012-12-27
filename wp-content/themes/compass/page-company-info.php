<?php get_header(); ?>

<section id="page">

	<?php get_template_part( 'loop', 'page' ); ?>
	
	<div id="leadership">
	
		<h2>Our Leaders</h2>
		
		<?php if(get_field('compass_leadership')): ?>
			<?php while (has_sub_field('compass_leadership')): ?>
			
				<div class="leader clearfix">
					<div class="leader-photo roundy">
						<img src="<?php the_sub_field('leadership_headshot') ?>" width="155" height="155"><br>
					</div><!-- .leader-photo -->
					<div class="leader-bio">
						<div class="leader-name"><?php the_sub_field('leadership_name') ?></div>	
						<div class="leader-position"><?php the_sub_field('leader_position') ?></div> 
						<div class="bio-intro">
							<?php the_sub_field('leadership_bio_1') ?>
						</div><!-- .bio-intro -->
						<div class="full-bio">
							<?php the_sub_field('leadership_bio_2') ?>
							<!-- <button>Read More</button> -->
							<span>Read More</span>
						</div><!-- .full-bio -->
					</div><!-- .leader-bio -->
				</div><!-- .leader -->
				
			<?php endwhile; ?>
		<?php endif; ?>
		<a href="<?php bloginfo('url') ?>/careers-opportunities" class="pull-right call-to-action">Wanna work at Compass?</a>
	</div><!-- #leadership -->	
	
</section><!-- #page -->

<?php get_footer(); ?>
