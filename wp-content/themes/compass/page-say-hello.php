<?php get_header(); ?>

	<section id="page" class="span12">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php if(get_field('subtitle')): ?>
				<h1><?php the_field('subtitle') ?></h1>
			<?php else: ?>
				<h1><?php the_title(); ?></h1>
			<?php endif; ?>	
		<?php endwhile; endif; ?>
		
		<div class="row">
			<div class="span12">
				<h2>Locations</h2>
				<?php if(get_field('offices')): ?>
					<?php while (has_sub_field('offices')): ?>
						<?php the_sub_field('office_city') ?><br>
						<?php the_sub_field('office_address') ?><br>
						<?php the_sub_field('office_phone') ?><br>
						<img src="http://maps.googleapis.com/maps/api/staticmap?center=<?php the_sub_field('office_address'); ?>,<?php the_sub_field('office_city'); ?> <?php the_sub_field('office_state'); ?>, <?php the_sub_field('office_zip'); ?>&zoom=16&size=330x200&maptype=roadmap&markers=color:red%7Ccolor:red%7Clabel:C%7C<?php the_sub_field('office_address'); ?>,<?php the_sub_field('office_city'); ?> <?php the_sub_field('office_state'); ?>, <?php the_sub_field('office_zip'); ?>&sensor=false" alt="Map" class="map"><br>
						<a href="http://maps.google.com/maps?saddr=&daddr=<?php the_sub_field('office_address'); ?>,<?php the_sub_field('office_city'); ?> <?php the_sub_field('office_state'); ?>, <?php the_sub_field('office_zip'); ?>">Get Directions</a>
					<?php endwhile; ?>
				<?php endif; ?>
			</div>
		</div><!-- .row -->
		
		<div class="row">
			<div class="span4" id="team">
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
			<div class="span8">
				<div class="row">
					<div class="span8" id="contact">
						<h2>Drop Us A Line</h2>
							<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
								<?php the_content(); ?>
							<?php endwhile; endif; ?>
					</div><!-- #contact -->
					<div class="span8" id="resources">
						<h2>Friends Who Can Help</h2>
						<?php if(get_field('friends_who_can_help')): ?>
							<?php while (has_sub_field('friends_who_can_help')): ?>
							<a href="<?php the_sub_field('friends_website_url') ?>"><?php the_sub_field('friends_name') ?></a><br>
								<?php the_sub_field('friends_phone_number') ?>
							<?php endwhile; ?>
						<?php endif; ?>
					</div><!-- #resources -->
				</div>f
			</div>
		</div><!-- .row -->
		
		
		
		
		
			
	</section><!-- #page -->

<?php get_footer(); ?>
