<?php get_header(); ?>

	<section id="page" class="contact-page">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div class="heading">
				<h1 class="section-header"><?php the_title(); ?><span class="subtitle"><?php the_field('subtitle') ?></span></h1>
				<div class="heading-arrow"></div>
			</div>
		<?php endwhile; endif; ?>
		
		<div class="tabbable tabs-left" id="locations-listing">
			<ul class="nav nav-tabs span6" id="locations">
				<?php if(get_field('offices')): ?>
					<?php while (has_sub_field('offices')): ?>
						<li>
							<a href="#<?php the_sub_field('office_location') ?>" data-toggle="tab">
								<span class="office-city"><?php the_sub_field('office_city') ?></span><br>	
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
						<div id="<?php the_sub_field('office_location') ?>" class="tab-pane">
							<img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php the_sub_field('office_address'); ?>,<?php the_sub_field('office_city'); ?> <?php the_sub_field('office_state'); ?>, <?php the_sub_field('office_zip'); ?>&zoom=16&size=638x468&maptype=roadmap&markers=color:red%7Ccolor:red%7C<?php the_sub_field('office_address'); ?>,<?php the_sub_field('office_city'); ?> <?php the_sub_field('office_state'); ?>, <?php the_sub_field('office_zip'); ?>&sensor=false" alt="Map" class="map"><br>
						<a href="http://maps.google.com/maps?saddr=&daddr=<?php the_sub_field('office_address'); ?>,<?php the_sub_field('office_city'); ?> <?php the_sub_field('office_state'); ?>, <?php the_sub_field('office_zip'); ?>" class="btn btn-inverse"><i class="icon-map-marker icon-white"></i> Get Directions</a>
						</div>
					<?php endwhile; ?>
				<?php endif; ?>
			</div><!-- #maps -->
		</div><!-- #locations-listing -->
				
		<div class="row say-hello-bottom">
			<div class="span6" id="team">
				<h2>Meet the Team</h2>
				<?php if(get_field('team_members')): ?>
					<?php while (has_sub_field('team_members')): ?>
						<div class="team-member">
							<div class="team-pic roundy">
								<img src="<?php the_sub_field('team_headshot') ?>" alt="<?php the_sub_field('team_name') ?>" width="155" height="155">
							</div>
							<div class="team-details">
								<span class="team-name"><strong><?php the_sub_field('team_first_name') ?></strong> <?php the_sub_field('team_last_name') ?></span>
								<span class="team-postion"><?php the_sub_field('team_position') ?></span>
								<span class="team-email"><a href="mailto:<?php the_sub_field('team_email') ?>"><?php the_sub_field('team_email') ?></a></span>
							</div>
						</div><!-- .team-member -->
					<?php endwhile; ?>
				<?php endif; ?>
			</div><!-- #team -->
			
			<div class="span10" id="right-col">
				<div class="row">
					<div class="span10" id="contact">
						<h2>Drop Us A Line</h2>
							<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
								<?php the_content(); ?>
							<?php endwhile; endif; ?>
					</div><!-- #contact -->
					<div class="clearfix"></div>
					<div class="" id="resources">
						<h2>Friends who can help</h2>
						<?php if(get_field('friends_who_can_help')): ?>
							<?php while (has_sub_field('friends_who_can_help')): ?>
							<div class="resource-name"><a href="<?php the_sub_field('friends_website_url') ?>"><?php the_sub_field('friends_name') ?></a>
							</div>
							<div class="resource-phone"><?php the_sub_field('friends_phone_number') ?></div>	
							<?php endwhile; ?>
						<?php endif; ?>
					</div><!-- #resources -->
				</div>
			</div>
		</div><!-- .row -->
			
	</section><!-- #page -->

<?php get_footer(); ?>
