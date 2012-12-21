<?php get_header(); ?>

	<section id="page" class="span16">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php if(get_field('subtitle')): ?>
				<h1><?php the_field('subtitle') ?></h1>
			<?php else: ?>
				<h1><?php the_title(); ?></h1>
			<?php endif; ?>	
		<?php endwhile; endif; ?>
		
		<div class="tabbable tabs-left" id="locations-listing">
			<ul class="nav nav-tabs span6" id="locations">
				<?php if(get_field('offices')): ?>
					<?php while (has_sub_field('offices')): ?>
						<li>
							<a href="#<?php the_sub_field('office_city') ?>" data-toggle="tab">
								<?php the_sub_field('office_city') ?><br>
								<?php the_sub_field('office_address') ?><br>
								<?php the_sub_field('office_phone') ?><br>
							</a>
						</li>
					<?php endwhile; ?>
				<?php endif; ?>
			</ul>
			<div class="tab-content span9" id="maps">
				<?php if(get_field('offices')): ?>
					<?php while (has_sub_field('offices')): ?>
						<div id="<?php the_sub_field('office_city') ?>" class="tab-pane">
							<img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php the_sub_field('office_address'); ?>,<?php the_sub_field('office_city'); ?> <?php the_sub_field('office_state'); ?>, <?php the_sub_field('office_zip'); ?>&zoom=16&size=638x468&maptype=roadmap&markers=color:red%7Ccolor:red%7C<?php the_sub_field('office_address'); ?>,<?php the_sub_field('office_city'); ?> <?php the_sub_field('office_state'); ?>, <?php the_sub_field('office_zip'); ?>&sensor=false" alt="Map" class="map"><br>
						<a href="http://maps.google.com/maps?saddr=&daddr=<?php the_sub_field('office_address'); ?>,<?php the_sub_field('office_city'); ?> <?php the_sub_field('office_state'); ?>, <?php the_sub_field('office_zip'); ?>">Get Directions</a>
						</div>
					<?php endwhile; ?>
				<?php endif; ?>
			</div><!-- #maps -->
		</div><!-- #locations-listing -->
				
		<div class="row">
			<div class="span6" id="team">
				<h2>Meet the Team</h2>
				<?php if(get_field('team_members')): ?>
					<?php while (has_sub_field('team_members')): ?>
						<img src="<?php the_sub_field('team_headshot') ?>" alt="<?php the_sub_field('team_name') ?>" width="100" height="100"><br>
						<?php the_sub_field('team_name') ?><br>
						<?php the_sub_field('team_position') ?><br>
						<a href="mailto:<?php the_sub_field('team_email') ?>"><?php the_sub_field('team_email') ?></a>
					<?php endwhile; ?>
				<?php endif; ?>
			</div><!-- #team -->
			<div class="span10">
				<div class="row">
					<div class="span10" id="contact">
						<h2>Drop Us A Line</h2>
							<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
								<?php the_content(); ?>
							<?php endwhile; endif; ?>
					</div><!-- #contact -->
					<div class="span10" id="resources">
						<h2>Friends Who Can Help You</h2>
						<?php if(get_field('friends_who_can_help')): ?>
							<?php while (has_sub_field('friends_who_can_help')): ?>
							<a href="<?php the_sub_field('friends_website_url') ?>"><?php the_sub_field('friends_name') ?></a><br>
								<?php the_sub_field('friends_phone_number') ?>
							<?php endwhile; ?>
						<?php endif; ?>
					</div><!-- #resources -->
				</div>
			</div>
		</div><!-- .row -->
		
		
		
		
		
			
	</section><!-- #page -->

<?php get_footer(); ?>
