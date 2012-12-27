<?php get_header(); ?>

<section id="page" class="careers-opps">
	<?php get_template_part( 'loop', 'page' ); ?>
		
	<div class="clearfix" id="staff-stories">
		
		<div id="staff-slider">
			<div class="scroll">
				<div class="scrollContainer">
					<?php if(get_field('career_success_stories')): ?>
						<?php while (has_sub_field('career_success_stories')): ?>
							<div class="panel" id="<?php the_sub_field('career_story_first-name') ?>">
								<div class="staff-headshot roundy">
									<img src="<?php the_sub_field('career_story_headshot') ?>" alt="<?php the_sub_field('career_story_name') ?>" width="265" height="265">
								</div>
								<div class="staff-info">
									<h2>Career Success Stories</h2>
									<div class="staff-name"> <?php the_sub_field('career_story_first_name') ?> <?php the_sub_field('career_story_last_name') ?>
									</div><!-- .staff-name -->
									<?php the_sub_field('career_success_story') ?>
								</div><!-- .staff-info -->
							</div>
						<?php endwhile; ?>
					<?php endif; ?>
				</div>
			</div>
			<ul class="navigation">
				<?php if(get_field('career_success_stories')): ?>
					<?php while (has_sub_field('career_success_stories')): ?>
						<li class="roundy">
							<a href="#<?php the_sub_field('career_story_first_name') ?>">
								<img src="<?php the_sub_field('career_story_headshot') ?>" alt="<?php the_sub_field('career_story_first_name') ?>" width="110" height="110"><br>
								<?php the_sub_field('career_story_first_name') ?>
							</a>
						</li>
					<?php endwhile; ?>
				<?php endif; ?>
			</ul>
		</div>
	</div><!-- .#staff-stories -->
	
	<div class="training">
		<h1>Training</h1>
		<div class="training-manuals">
			<?php if(get_field('training_resources')): ?>
				<?php while (has_sub_field('training_resources')): ?>
					<a href="<?php the_sub_field('training_file') ?>" title="<?php the_sub_field('training_title') ?>"><?php the_sub_field('training_title') ?></a>
				<?php endwhile; ?>
			<?php endif; ?>
		</div><!-- .training-manuals -->
		<a href="<?php bloginfo('url'); ?>/jobs" class="btn pull-right">Go to Job Listings</a>
	</div><!-- .training -->
	
</section><!-- #page -->

<?php get_footer(); ?>
