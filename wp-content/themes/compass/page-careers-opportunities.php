<?php get_header(); ?>

<section id="page">
	<div class="row">
		<div class="span12">
			<?php get_template_part( 'loop', 'page' ); ?>
		</div>
	</div>
		
	<div class="span12" id="staff-stories">
		<h2>Career Success Stories</h2>
		<div id="staff-slider">
			<div class="scroll">
				<div class="scrollContainer">
					<?php if(get_field('career_success_stories')): ?>
						<?php while (has_sub_field('career_success_stories')): ?>
							<div class="panel" id="<?php the_sub_field('career_story_first-name') ?>">
								<img src="<?php the_sub_field('career_story_headshot') ?>" alt="<?php the_sub_field('career_story_name') ?>" width="100" height="100"><br>
								<?php the_sub_field('career_story_first_name') ?> <?php the_sub_field('career_story_last_name') ?><br>
								<?php the_sub_field('career_success_story') ?><br>
							</div>
						<?php endwhile; ?>
					<?php endif; ?>
				</div>
			</div>
			<ul class="navigation">
				<?php if(get_field('career_success_stories')): ?>
					<?php while (has_sub_field('career_success_stories')): ?>
						<li><a href="#<?php the_sub_field('career_story_first_name') ?>"><img src="<?php the_sub_field('career_story_headshot') ?>" alt="<?php the_sub_field('career_story_first_name') ?>" width="50" height="50"><br><?php the_sub_field('career_story_first_name') ?></a></li>
					<?php endwhile; ?>
				<?php endif; ?>
			</ul>
		</div>
	</div><!-- .#staff-stories -->
	
	<h1>Training</h1>
	<?php if(get_field('training_resources')): ?>
		<?php while (has_sub_field('training_resources')): ?>
			<a href="<?php the_sub_field('training_file') ?>" title="<?php the_sub_field('training_title') ?>"><?php the_sub_field('training_title') ?></a><br>
		<?php endwhile; ?>
	<?php endif; ?>
</section><!-- #page -->

<?php get_footer(); ?>
